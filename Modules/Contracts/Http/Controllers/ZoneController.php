<?php

namespace Modules\Contracts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contracts\Entities\Zone;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $zones = Zone::paginate(10);
        return view('contracts::zones.index', compact('zones'));
    }

    public function create()
    {
        return view('contracts::zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Zone::create($request->only('name', 'description'));

        return redirect()->route('zones.index')->with('success', 'Zona creada correctamente.');
    }

    public function show(Zone $zone)
    {
        return view('contracts::zones.show', compact('zone'));
    }

    public function edit(Zone $zone)
    {
        return view('contracts::zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $zone->update($request->only('name', 'description'));

        return redirect()->route('zones.index')->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy(Zone $zone)
    {
        if ($zone->packages()->exists()) {
            return redirect()->route('zones.index')->with('error', 'No se puede eliminar porque tiene paquetes asociados.');
        }
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zona eliminada correctamente.');
    }
}