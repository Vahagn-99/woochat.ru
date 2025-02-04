<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerateAccessTokenController extends Controller
{
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $user->tokens()->delete();

        return response()->json([
            'access_token' => $user->createToken('amocrm')->plainTextToken,
        ]);
    }
}
