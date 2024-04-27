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

        if ($user !== null
            && hash('sha256', $password) === $user->hidden['password']
            && $user->fillable['username'] === $username) {

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => [
                    'username' => $user->fillable['username'],
                    'id' => $user->fillable['id']
                ]
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

        $user = User::where('username', $username);

        if ($user === null) {
            $user = new User();
            $user->fillable['username'] = $username;
            $user->hidden['password'] = hash('sha256', $password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User created',
                'data' => [
                    'username' => $user->fillable['username'],
                    'id' => $user->fillable['id']
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User already exists'
            ]);
        }
    }
}
