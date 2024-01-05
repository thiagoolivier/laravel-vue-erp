<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    #[Get(uri: "/user/{id}", name: "user.show")]
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    #[Post(uri: "/user", name: "user.store")]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'email' => 'required|email|max:50|min:3',
            'password' => 'required|max:32|min:8',
        ]);

        $user = new User();

        $user->save([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json($user, 201);
    }

    #[Put(uri: "/user/{id}", name: "user.update")]
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|min:3',
            'email' => 'required|email|max:50|min:3',
            'password' => 'required|max:32|min:8',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    #[Delete(uri: "/user/{id}", name: "user.delete")]
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        try {
            $user->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error during resource deletion'], 500);
        }
    }
}
