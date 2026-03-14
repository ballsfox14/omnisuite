<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\Tool;

class ToolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver herramientas'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear herramientas'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar herramientas'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar herramientas'])->only(['destroy']);
    }

    public function index()
    {
        $tools = Tool::paginate(10);
        return view('inventory::tools.index', compact('tools'));
    }

    public function create()
    {
        return view('inventory::tools.create');
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'tipo' => 'required|string|max:50',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'accesorios' => 'nullable|array',
            'accesorios.*' => 'string|max:255',
        ]);

        // Para depurar: descomenta la siguiente línea para ver qué llega
        // dd($request->all());

        // Si no llegan accesorios, aseguramos un array vacío
        $accesorios = $request->accesorios ?? [];

        $baseCode = strtoupper($request->tipo . '-' . $request->marca . '-' . $request->modelo);
        $lastTool = Tool::where('code', 'LIKE', $baseCode . '%')->orderBy('code', 'desc')->first();
        $lastNumber = $lastTool ? (int) substr($lastTool->code, -3) : 0;

        for ($i = 1; $i <= $request->quantity; $i++) {
            $newNumber = str_pad($lastNumber + $i, 3, '0', STR_PAD_LEFT);
            $code = $baseCode . '-' . $newNumber;

            Tool::create([
                'name' => $request->name,
                'tipo' => $request->tipo,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'code' => $code,
                'description' => $request->description,
                'quantity' => 1,
                'accesorios' => $accesorios,
            ]);
        }

        return redirect()->route('inventory.tools.index')
            ->with('success', $request->quantity . ' herramienta(s) creada(s) correctamente.');
    }

    public function show(Tool $tool)
    {
        return view('inventory::tools.show', compact('tool'));
    }

    public function edit(Tool $tool)
    {
        return view('inventory::tools.edit', compact('tool'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tipo' => 'required|string|max:50',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'description' => 'nullable|string',
            // 'quantity' no se actualiza porque cada herramienta es una unidad
            'accesorios' => 'nullable|array',
            'accesorios.*' => 'string|max:255',
        ]);

        // Para depurar: descomenta la siguiente línea
        // dd($request->all());

        $tool->update([
            'name' => $request->name,
            'tipo' => $request->tipo,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'description' => $request->description,
            'accesorios' => $request->accesorios,
        ]);

        return redirect()->route('inventory.tools.index')
            ->with('success', 'Herramienta actualizada correctamente.');
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return redirect()->route('inventory.tools.index')
            ->with('success', 'Herramienta eliminada correctamente.');
    }
}