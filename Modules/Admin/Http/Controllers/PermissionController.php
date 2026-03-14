<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver permisos'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear permisos'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar permisos'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar permisos'])->only(['destroy']);
    }

    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('admin::permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin::permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso creado correctamente.');
    }

    public function show(Permission $permission)
    {
        return view('admin::permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('admin::permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permiso eliminado correctamente.');
    }
}