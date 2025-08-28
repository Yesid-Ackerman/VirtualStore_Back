<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
   public function index()
    {
        return response()->json(Role::all(), 200);
    }

    /**
     * Crear un nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        return response()->json($role, 201);
    }

    /**
     * Mostrar un rol específico
     */
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json($role, 200);
    }

    /**
     * Actualizar un rol
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role->update([
            'name' => $request->name
        ]);

        return response()->json($role, 200);
    }

    /**
     * Eliminar un rol
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Rol eliminado correctamente'], 200);
    }
}
