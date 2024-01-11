<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class PermissionController extends Controller
{
    #[Get('permissions', name: "permission.index")]
    public function index(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }

    #[Get('permission/{id}', name: "permission.show")]
    public function show($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);

        return response()->json($permission);
    }

    #[Post('permission', name: "permission.store")]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:50|min:3',
        ]);

        $permission = Permission::create($request->all());

        return response()->json(['message' => 'Permission successfuly created.'], 201);
    }

    #[Put('permission/{id}', name: "permission.update")]
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

    #[Delete('permission/{id}', name: "permission.update")]
    public function delete($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Not found.'], 404);
        }

        try {
            $permission->delete();        
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error during deletion.'], 500);
        }
    }
}
