<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Middleware('auth:api')]
class PermissionController extends Controller
{
    #[Get('permissions', name: "permissions.index")]
    public function index(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }

    #[Get('permissions/{id}', name: "permissions.show")]
    public function show($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);

        return response()->json($permission);
    }
}
