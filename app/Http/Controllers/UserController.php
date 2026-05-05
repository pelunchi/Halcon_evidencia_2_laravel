<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = ['Admin', 'Ventas', 'Almacen', 'Compras', 'Ruta'];

    /**
     * General list of all users (active and inactive).
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show creation form.
     */
    public function create()
    {
        $roles = self::ROLES;
        return view('users.create', compact('roles'));
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => ['required', Rule::in(self::ROLES)],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['active']   = true;

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        $roles = self::ROLES;
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user data, role, or active status.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(self::ROLES)],
            'active'   => 'boolean',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['active'] = $request->boolean('active');

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
