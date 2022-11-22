<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::firstOrCreate([
            'email' => $data['email']
        ], $data);

        if ($user) {
            event(new Registered($user));
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'auth_user' => new UserResource($user)
            ], 200);
        }

//        return new UserResource($user);

    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();


        if (!$this->guard()->attempt($credentials)) {
            return response()->json([

                'message' => 'The provided credentials are incorrect.'
            ], 422);
        }
        $this->guard()->attempt($credentials);
        $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

        $user = $this->guard()->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'auth_user' => new UserResource($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $this->guard()->logout();
        return response()->json([
            'message' => 'logged out successfully'
        ], 200);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

}
