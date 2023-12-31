<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function show($id): JsonResponse
    {
        $role = Role::findOrFail($id);

        return response()->json($role);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:50|min:3',
        ]);

        $role = Role::create($request->all());

        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:50|min:3',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return response()->json($role);
    }

    public function delete($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }

        try {
            $role->delete();        
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro durante a exclusão do recurso'], 500);
        }
    }
}
