<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver usuarios'])->only(['index', 'show']);
        $this->middleware(['auth', 'can:crear usuarios'])->only(['create', 'store']);
        $this->middleware(['auth', 'can:editar usuarios'])->only(['edit', 'update']);
        $this->middleware(['auth', 'can:eliminar usuarios'])->only(['destroy']);
    }

    public function index()
    {
        $users = User::with('roles', 'permissions')->paginate(10);
        return view('admin::users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $areas = Area::all();
        return view('admin::users.create', compact('roles', 'permissions', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'permissions' => 'array',
            'contract_type' => 'required|in:full_time,part_time,custom',
            'weekly_hours' => 'nullable|numeric|min:0|max:168',
            'rest_day' => 'nullable|integer|min:0|max:6',
            'area_id' => 'nullable|exists:areas,id',
            'initial_balance' => 'nullable|numeric', // nuevo campo
        ]);

        // Generar código de empleado automáticamente
        $lastUser = User::orderBy('id', 'desc')->first();
        $nextId = $lastUser ? $lastUser->id + 1 : 1;
        $employeeCode = 'OMN-EMP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'area_id' => $request->area_id,
            'employee_code' => $employeeCode,
            'contract_type' => $request->contract_type,
            'weekly_hours' => $request->weekly_hours,
            'rest_day' => $request->rest_day,
            'initial_balance' => $request->initial_balance ?? 0,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user)
    {
        return view('admin::users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $areas = Area::all();
        return view('admin::users.edit', compact('user', 'roles', 'permissions', 'areas'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'permissions' => 'array',
            'contract_type' => 'required|in:full_time,part_time,custom',
            'weekly_hours' => 'nullable|numeric|min:0|max:168',
            'rest_day' => 'nullable|integer|min:0|max:6',
            'area_id' => 'nullable|exists:areas,id',
            'initial_balance' => 'nullable|numeric',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'area_id' => $request->area_id,
            'contract_type' => $request->contract_type,
            'weekly_hours' => $request->weekly_hours,
            'rest_day' => $request->rest_day,
            'initial_balance' => $request->initial_balance ?? 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}