<?php

namespace Modules\Contracts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contracts\Entities\Package;
use Modules\Contracts\Entities\Zone;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $packages = Package::with('zones')->paginate(10);
        return view('contracts::packages.index', compact('packages'));
    }

    public function create()
    {
        $zones = Zone::all();
        return view('contracts::packages.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric',
            'zones' => 'nullable|array',
            'zones.*.id' => 'exists:zones,id',
            'zones.*.price' => 'nullable|numeric|min:0', // precio opcional
        ]);

        $package = Package::create($request->only('name', 'description', 'base_price'));

        if ($request->has('zones')) {
            $syncData = [];
            foreach ($request->zones as $zone) {
                if (!is_null($zone['price'])) {
                    $syncData[$zone['id']] = ['price' => $zone['price']];
                }
            }
            $package->zones()->sync($syncData);
        }

        return redirect()->route('packages.index')->with('success', 'Paquete creado correctamente.');
    }

    public function show(Package $package)
    {
        $package->load('zones');
        return view('contracts::packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $zones = Zone::all();
        $package->load('zones');
        return view('contracts::packages.edit', compact('package', 'zones'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric',
            'zones' => 'nullable|array',
            'zones.*.id' => 'exists:zones,id',
            'zones.*.price' => 'nullable|numeric|min:0',
        ]);

        $package->update($request->only('name', 'description', 'base_price'));

        if ($request->has('zones')) {
            $syncData = [];
            foreach ($request->zones as $zone) {
                if (!is_null($zone['price'])) {
                    $syncData[$zone['id']] = ['price' => $zone['price']];
                }
            }
            $package->zones()->sync($syncData);
        } else {
            $package->zones()->sync([]);
        }

        return redirect()->route('packages.index')->with('success', 'Paquete actualizado correctamente.');
    }

    public function destroy(Package $package)
    {
        if ($package->contracts()->exists()) {
            return redirect()->route('packages.index')->with('error', 'No se puede eliminar porque hay contratos asociados.');
        }
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Paquete eliminado correctamente.');
    }
}