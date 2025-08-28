<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query()
            ->included()   // relaciones
            ->filter()     // filtros
            ->get();

        return response()->json($roles);
    }

    /**
     * Crear un nuevo rol.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create($request->all());

        return response()->json([
            'message' => 'Rol creado exitosamente',
            'data' => $role
        ], 201);
    }

    /**
     * Mostrar un rol específico.
     */
    public function show(Role $role)
    {
        $role->load(request('included', [])); // carga relaciones si se piden

        return response()->json($role);
    }

    /**
     * Actualizar un rol.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());

        return response()->json([
            'message' => 'Rol actualizado exitosamente',
            'data' => $role
        ]);
    }

    /**
     * Eliminar un rol.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente'
        ]);
    }
}
