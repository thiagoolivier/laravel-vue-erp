<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

#[Middleware('auth:api')]
class RoleController extends Controller
{
    #[Get(uri:"/roles", name:"roles.index")]
    public function index(): JsonResponse
    {
        $roles = Role::getRoles();

        return response()->json(['roles' => $roles]);
    }

    #[Get(uri:"/roles/{id}", name:"roles.show")]
    public function show($id): JsonResponse
    {
        try {
            return response()->json(
                Role::findOrFail($id), 
                200
            );
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot find the requested register.'], 500);
        }
    }

    #[Post(uri:"/roles", name:"roles.store")]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:200|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        Role::create($request->only(['name', 'description']));

        Cache::forget('roles');

        return response()->json(['message' => 'Role successfuly created.'], 201);
    }

    #[Put(uri:"/roles/{id}", name:"roles.update")]
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:200|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $role = Role::findOrFail($id);
        $role->update($request->all());

        Cache::forget('roles');

        return response()->json(['message' => 'Role successfuly updated.']);
    }

    #[Delete(uri:"/roles/{id}", name:"roles.delete")]
    public function delete(int $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if(sizeof($role->users) > 0) {
            return response()->json(['message' => "Can't delete the role. There are users using it."], 500);
        };

        try {
            $role->delete();

            Cache::forget('roles');
            
            return response()->json(status: 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error during resource deletion'], 500);
        }
    }

    #[Get(uri: "/roles/{id}/permissions", name: "roles.permissions")]
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

    #[Post(uri: "/roles/{id}/permissions", name: "roles.permissions_edit")]
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
