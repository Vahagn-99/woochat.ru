<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->string('password')),
        ]);

        $accessToken = $user->createToken('auth_token')->plainTextToken;

        event(new Registered($user));

        return response()->json([
            'message' => 'Register successfully',
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
        ]);
    }
}