<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

class UserController extends Controller
{
    #[Get(uri: '/users', name: 'users.index')]
    public function index(Request $request){
        $users = User::query()
            ->when($request->input('id'), function($q) use ($request) {
                $q->where('id', $request->input('id'));
            })
            ->when($request->input('name'), function($q) use ($request) {
                $q->where('name', $request->input('name'));
            })
            ->when($request->input('email'), function($q) use ($request) {
                $q->where('email', $request->input('email'));
            })
            ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->get();

        return response()->json([
            "users" => $users,
        ]);
    }
}
