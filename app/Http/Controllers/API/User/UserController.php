<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

class UserController extends Controller
{
    #[Get(uri: '/users', name: 'users.index')]
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->input('id'), function ($q) use ($request) {
                $q->where('id', $request->input('id'));
            })
            ->when($request->input('name'), function ($q) use ($request) {
                $q->where('name', $request->input('name'));
            })
            ->when($request->input('email'), function ($q) use ($request) {
                $q->where('email', $request->input('email'));
            })
            ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->get();

        return response()->json([
            "users" => $users,
        ]);
    }

    #[Get(uri: "/users/{id}", name: "users.show")]
    public function show(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    #[Post(uri: "/users", name: "users.store")]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'email' => 'required|email|max:50|min:3|unique:App\Models\User,email',
            'password' => 'required|max:32|min:8',
        ]);

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json(['message' => 'User successfuly created.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot register the user.'], 500);
        }        
    }

    #[Put(uri: "/users/{id}", name: "users.update")]
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'string|max:50|min:3',
            'email' => 'email|max:50|min:3',
        ]);

        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    #[Delete(uri: "/users/{id}", name: "users.delete")]
    public function delete(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->user()) {
            return response()->json(["message"=> "The authenticated user can't be deleted."], 403);
        }

        if ($user->roles->count() > 0) {
            return response()->json(['message' => "Can't delete the user because there are related roles."], 403);
        }

        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error during resource deletion'], 500);
        }
    }

    #[Get(uri: "/users/{id}/roles", name: "users.roles")]
    public function getUserRoles(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $roles = $user->roles;
            return response()->json(['roles' => $roles], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Cannot find the requested register.'], 500);
        }
    }

    #[Put(uri: "/users/{id}/roles", name: "users.roles_edit")]
    public function editUserRoles($id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->roles()->sync($request->input('roles'));

        return response()->json([
            'message' => ($user->name . ' roles updated successfuly.')
        ], 201);
    }
}
