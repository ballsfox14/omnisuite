<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Entities\Kit;
use Modules\Inventory\Entities\Loan;
use Modules\Inventory\Entities\LoanItem;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class MobileLoanController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    /**
     * Muestra la vista de préstamo rápido para un kit
     */
    public function showKit($code)
    {
        $kit = Kit::where('code', $code)
            ->with('tools')
            ->firstOrFail();

        $users = User::with('area')->orderBy('name')->get();

        return view('mobile.kit-preview', compact('kit', 'users'));
    }

    /**
     * Procesa el préstamo rápido desde móvil
     */
    public function store(Request $request)
    {
        $request->validate([
            'kit_id' => 'required|exists:kits,id',
            'user_id' => 'required|exists:users,id', // principal
            'additional_users' => 'nullable|array',
            'additional_users.*' => 'exists:users,id|different:user_id',
            'loan_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $kit = Kit::with('tools')->findOrFail($request->kit_id);

        // Verificar disponibilidad de todas las herramientas del kit
        foreach ($kit->tools as $tool) {
            if ($tool->quantity < $tool->pivot->quantity) {
                return back()->with('error', "No hay suficiente stock de {$tool->name} para este kit.");
            }
        }

        // Calcular team_size (principal + adicionales)
        $teamSize = 1 + count($request->additional_users ?? []);

        // Crear préstamo
        $loan = Loan::create([
            'user_id' => $request->user_id,
            'notes' => $request->notes,
            'loaned_at' => $request->loan_date,
            'team_size' => $teamSize,
        ]);

        activity()
            ->performedOn($loan)
            ->causedBy(auth()->user())
            ->withProperties([
                'kit' => $kit->name,
                'tools' => $kit->tools->map(fn($t) => $t->name . ' x' . $t->pivot->quantity),
                'team_size' => $teamSize,
                'additional_users' => $request->additional_users ?? []
            ])
            ->log('Préstamo rápido realizado');

        // Sincronizar usuarios adicionales
        if ($request->has('additional_users')) {
            $loan->users()->sync($request->additional_users);
        }

        // Crear items del préstamo (herramientas del kit)
        foreach ($kit->tools as $tool) {
            // Descontar stock
            $tool->decrement('quantity', $tool->pivot->quantity);

            // Registrar item
            $loan->items()->create([
                'loanable_type' => get_class($tool),
                'loanable_id' => $tool->id,
                'quantity' => $tool->pivot->quantity,
            ]);
        }

        return redirect()->route('inventory.loans.index')
            ->with('success', 'Préstamo rápido registrado correctamente.');
    }
}