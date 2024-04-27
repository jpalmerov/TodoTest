<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username);


        if (hash('sha256', $password) === $user->password
            && $user->username === $username) {

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ]);
        }

    }
}
