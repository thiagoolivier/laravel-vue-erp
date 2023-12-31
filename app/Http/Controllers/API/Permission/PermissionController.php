<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }

    public function show($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);

        return response()->json($permission);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:50|min:3',
        ]);

        $permission = Permission::create($request->all());

        return response()->json($permission, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:50|min:3',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($request->all());

        return response()->json($permission);
    }

    public function delete($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }

        try {
            $permission->delete();        
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro durante a exclusão do recurso'], 500);
        }
    }
}
