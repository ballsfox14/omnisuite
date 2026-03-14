<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\Loan;
use Modules\Inventory\Entities\Tool;
use Modules\Inventory\Entities\Kit;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver prestamos'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear prestamos'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar prestamos'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar prestamos'])->only(['destroy']);
        $this->middleware(['auth', 'can:devolver prestamos'])->only(['return']);
    }

    public function index()
    {
        // Cargamos las relaciones necesarias: user, items.loanable, y users (responsables)
        $loans = Loan::with(['user', 'items.loanable', 'users'])
            ->orderBy('loaned_at', 'desc')
            ->paginate(10);

        return view('inventory::loans.index', compact('loans'));
    }

    public function create()
    {
        $tools = Tool::where('quantity', '>', 0)->get();
        $kits = Kit::all();
        $users = User::with('area')->orderBy('name')->get(); // todos los empleados

        return view('inventory::loans.create', compact('tools', 'kits', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // principal obligatorio
            'additional_users' => 'nullable|array',
            'additional_users.*' => 'exists:users,id|different:user_id', // no puede repetir principal
            'notes' => 'nullable|string',
            'loaned_at' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:tool,kit',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calcular team_size (total de personas)
        $team_size = 1 + count($request->additional_users ?? []);

        // Crear préstamo
        $loan = Loan::create([
            'user_id' => $request->user_id,
            'notes' => $request->notes,
            'loaned_at' => $request->loaned_at,
            'team_size' => $team_size, // si decides guardarlo
        ]);

        // En LoanController@return, después de actualizar el préstamo
        activity()
            ->performedOn($loan)
            ->causedBy(auth()->user())
            ->withProperties(['returned_at' => now()])
            ->log('Préstamo devuelto');

        // Sincronizar empleados adicionales (excluyendo al principal)
        if ($request->has('additional_users')) {
            $loan->users()->sync($request->additional_users);
        }

        // Procesar items (igual que antes)
        foreach ($request->items as $itemData) {
            if ($itemData['type'] == 'tool') {
                $tool = Tool::find($itemData['id']);
                if (!$tool || $tool->quantity < $itemData['quantity']) {
                    return redirect()->back()->withErrors(['items' => "No hay suficiente stock de la herramienta {$tool->name}"]);
                }
                $tool->decrement('quantity', $itemData['quantity']);

                $loan->items()->create([
                    'loanable_type' => Tool::class,
                    'loanable_id' => $tool->id,
                    'quantity' => $itemData['quantity'],
                ]);
            } else { // kit
                $kit = Kit::with('tools')->find($itemData['id']);
                if (!$kit)
                    continue;

                $available = true;
                foreach ($kit->tools as $tool) {
                    if ($tool->quantity < ($tool->pivot->quantity * $itemData['quantity'])) {
                        $available = false;
                        break;
                    }
                }
                if (!$available) {
                    return redirect()->back()->withErrors(['items' => "No hay suficiente stock para el kit {$kit->name}"]);
                }

                foreach ($kit->tools as $tool) {
                    $tool->decrement('quantity', $tool->pivot->quantity * $itemData['quantity']);
                }

                $loan->items()->create([
                    'loanable_type' => Kit::class,
                    'loanable_id' => $kit->id,
                    'quantity' => $itemData['quantity'],
                ]);
            }
        }

        return redirect()->route('inventory.loans.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    public function show(Loan $loan)
    {
        // Cargamos todas las relaciones necesarias
        $loan->load(['user', 'items.loanable', 'users.area']);

        return view('inventory::loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        // Normalmente no se editan préstamos, pero si quieres puedes implementarlo
        // Por ahora redirigimos al índice con un mensaje
        return redirect()->route('inventory.loans.index')
            ->with('error', 'No se puede editar un préstamo.');
    }

    public function update(Request $request, Loan $loan)
    {
        // Misma razón, no implementado
        return redirect()->route('inventory.loans.index')
            ->with('error', 'No se puede editar un préstamo.');
    }

    public function return(Loan $loan)
    {
        if (!$loan->isActive()) {
            return redirect()->back()->with('error', 'Este préstamo ya fue devuelto.');
        }

        // Devolver stock
        foreach ($loan->items as $item) {
            $loanable = $item->loanable;
            if ($loanable instanceof Tool) {
                $loanable->increment('quantity', $item->quantity);
            } elseif ($loanable instanceof Kit) {
                $kit = $loanable->load('tools');
                foreach ($kit->tools as $tool) {
                    $tool->increment('quantity', $tool->pivot->quantity * $item->quantity);
                }
            }
        }

        $loan->update(['returned_at' => now()]);

        return redirect()->route('inventory.loans.index')
            ->with('success', 'Préstamo devuelto correctamente.');
    }

    public function destroy(Loan $loan)
    {
        if ($loan->isActive()) {
            return redirect()->back()->with('error', 'No se puede eliminar un préstamo activo. Debe devolverlo primero.');
        }

        $loan->delete();
        return redirect()->route('inventory.loans.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }
}