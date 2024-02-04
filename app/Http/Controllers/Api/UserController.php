<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

        } catch (ValidationException $e){

            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);

        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'success' => true,
            'token' => $user->createToken('User')->plainTextToken,
        ], 201);

    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e){

            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);

        }

        if (Auth::attempt($validatedData)) {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'token' => $this->generateToken($user),
            ], 200);

        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    private function generateToken(User $user)
    {
        $user->tokens()->delete();
        return $user->createToken(now()->toDateTimeString())->plainTextToken;;
    }

}
