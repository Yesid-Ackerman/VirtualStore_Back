<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios con scopes
     */
    public function index()
    {
        // Usa los scopes incluidos en el modelo
        $users = User::included()->filter()->get();

        return response()->json($users, 200);
    }

    /**
     * Mostrar un usuario específico
     */
    public function show(User $user)
    {
        // Aplicamos included al usuario individual
        $user->load(request('included', []));

        return response()->json($user, 200);
    }

    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $request->role_id
        ]);

        return response()->json($user, 201);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'string|max:255',
            'email'    => 'string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id'  => 'exists:roles,id'
        ]);

        $user->update([
            'name'     => $request->name ?? $user->name,
            'email'    => $request->email ?? $user->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role_id'  => $request->role_id ?? $user->role_id
        ]);

        return response()->json($user, 200);
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }
}
