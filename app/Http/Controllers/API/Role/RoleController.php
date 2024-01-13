<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class RoleController extends Controller
{
    #[Get(uri:"/roles", name:"role.index")]
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json(['roles' => $roles]);
    }

    #[Get(uri:"/role/{id}", name:"role.show")]
    public function show($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);

            return response()->json($role, 200);//code...
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot find the requested register.'], 500);
        }
    }

    #[Post(uri:"/role", name:"role.store")]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'description' => 'required|string|max:200|min:3',
        ]);

        Role::create($request->all());

        return response()->json(['message' => 'Role successfuly created.'], 201);
    }

    #[Put(uri:"/role/{id}", name:"role.update")]
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

    #[Delete(uri:"/role/{id}", name:"role.delete")]
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
}
