<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver areas'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear areas'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar areas'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar areas'])->only(['destroy']);
    }

    public function index()
    {
        $areas = Area::paginate(10);
        return view('admin::areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin::areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
            'description' => 'nullable|string',
        ]);

        Area::create($request->only('name', 'description'));

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área creada correctamente.');
    }

    public function edit(Area $area)
    {
        return view('admin::areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'description' => 'nullable|string',
        ]);

        $area->update($request->only('name', 'description'));

        return redirect()->route('admin.areas.index')
            ->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        // Verificar si tiene usuarios asociados
        if ($area->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el área porque tiene usuarios asignados.');
        }

        $area->delete();
        return redirect()->route('admin.areas.index')
            ->with('success', 'Área eliminada correctamente.');
    }
}