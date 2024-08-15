<?php

namespace App\Http\Controllers\AmoCRM;

use App\Events\AmoCRM\PhoneNumberReceived;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\PhoneNumberRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PhoneNumberController extends Controller
{
    public function __invoke(PhoneNumberRequest $request, User $user): JsonResponse
    {
        $user->phone = $request->input('phone');
        $user->save();

        PhoneNumberReceived::dispatch($user);

        return response()->json();
    }
}
