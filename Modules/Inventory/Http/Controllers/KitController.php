<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\Kit;
use Modules\Inventory\Entities\Tool;

class KitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver kits'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear kits'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar kits'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar kits'])->only(['destroy']);
    }

    public function index()
    {
        $kits = Kit::paginate(10);
        return view('inventory::kits.index', compact('kits'));
    }

    public function create()
    {
        $tools = Tool::all();
        return view('inventory::kits.create', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tools' => 'required|array|min:1',
            'tools.*.id' => 'required|exists:tools,id',
            'tools.*.quantity' => 'required|integer|min:1',
        ]);

        // Crear kit (el código se genera automáticamente en el modelo)
        $kit = Kit::create($request->only('name', 'description'));

        // Sincronizar herramientas con cantidades
        $syncData = [];
        foreach ($request->tools as $toolData) {
            $syncData[$toolData['id']] = ['quantity' => $toolData['quantity']];
        }
        $kit->tools()->sync($syncData);

        return redirect()->route('inventory.kits.index')
            ->with('success', 'Kit creado correctamente. Código generado: ' . $kit->code);
    }

    public function show(Kit $kit)
    {
        $kit->load('tools');
        return view('inventory::kits.show', compact('kit'));
    }

    public function edit(Kit $kit)
    {
        $tools = Tool::all();
        $kit->load('tools');
        return view('inventory::kits.edit', compact('kit', 'tools'));
    }

    public function update(Request $request, Kit $kit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tools' => 'required|array|min:1',
            'tools.*.id' => 'required|exists:tools,id',
            'tools.*.quantity' => 'required|integer|min:1',
        ]);

        $kit->update($request->only('name', 'description'));

        $syncData = [];
        foreach ($request->tools as $toolData) {
            $syncData[$toolData['id']] = ['quantity' => $toolData['quantity']];
        }
        $kit->tools()->sync($syncData);

        return redirect()->route('inventory.kits.index')
            ->with('success', 'Kit actualizado correctamente.');
    }

    public function destroy(Kit $kit)
    {
        // Verificar si el kit tiene préstamos activos
        if ($kit->loans()->whereNull('returned_at')->exists()) {
            return redirect()->route('inventory.kits.index')
                ->with('error', 'No se puede eliminar un kit con préstamos activos.');
        }

        $kit->delete();
        return redirect()->route('inventory.kits.index')
            ->with('success', 'Kit eliminado correctamente.');
    }
}