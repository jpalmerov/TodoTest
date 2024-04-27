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

        $user = User::get($username);

        if ($user !== null
            && hash('sha256', $password) === $user->hidden['password']
            && $user->fillable['username'] === $username) {

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => $user->data()
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ]);
        }

    }

    public function create(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $password = hash('sha256', $request->input('password'));

        $user = User::get($username);

        if ($user === null) {
            $inserted = User::query()->insert([
                'username' => $username,
                'password' => $password
            ]);

            if (!$inserted) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not created'
                ]);
            }

            $user = User::get($username);

            return response()->json([
                'status' => 'success',
                'message' => 'User created',
                'data' => $user->data()
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User already exists'
            ]);
        }
    }
}
