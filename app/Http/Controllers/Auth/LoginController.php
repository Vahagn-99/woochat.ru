<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Logged in successfully.',
            'access_token' => $request->authenticate()
        ]);
    }
}
