<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class RoleController extends Controller
{
    #[Get(uri:"/roless", name:"role.index")]
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json(['roles' => $roles]);
    }

    #[Get(uri:"/roles/{id}", name:"role.show")]
    public function show($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot find the requested register.'], 500);
        }
    }

    #[Post(uri:"/roles", name:"role.store")]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:200|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        Role::create($request->all());

        return response()->json(['message' => 'Role successfuly created.'], 201);
    }

    #[Put(uri:"/roles/{id}", name:"role.update")]
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:4|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return response()->json($role);
    }

    #[Delete(uri:"/roles/{id}", name:"role.delete")]
    public function delete($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Not found'], 404);
        }

        try {
            $role->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error during resource deletion'], 500);
        }
    }

    #[Get(uri: "/roles/{id}/permissions", name: "role.permissions")]
    public function getRolePermissions($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = $role->permissions;
            return response()->json(['permissions' => $permissions], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot find the requested register.'], 500);
        }
    }

    #[Post(uri: "/roles/{id}/permissions", name: "role.store")]
    public function editRolePermissions($id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',  
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found.'], 404);
        }

        $role->permissions()->sync($request->input('permissions'));

        return response()->json([
            'message' => ($role->name . ' permissions updated successfuly.')
        ], 201);
    }
}
