<?php

namespace App\Http\Controllers\AmoCRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\Oauth\CallbackRequest;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DctAuthController extends Controller
{
    public function callback(CallbackRequest $request): JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");
        do_log("amocrm/auth-callback")->notice("$domain was triggered");

        $accessToken = Amo::main()->authenticator()->exchangeCodeWithAccessToken($code);

        Storage::disk('dct')->put('/amocrm/access_token.json', json_encode($accessToken, JSON_PRETTY_PRINT));

        do_log("amocrm/auth-callback")->info("$domain was authenticated successfully");

        return response()->json(['success' => true]);
    }
}