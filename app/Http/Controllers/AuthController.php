<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use App\Http\Requests\{
    LoginRequest,
    RegisterRequest   
};
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Enums\Role;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => Role::USER,               // default viewer
        ]);
        $token = $user->createToken($user->email);
        return response()->json(['token'=>$token], 201);
    }


    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();
    
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $token = $user->createToken($user->email);
    
        return response()->json(['token' => $token], 201);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
    
        return response()->json(['message' => 'logged out successfuly'], 200);
    }
}
