<?php

namespace App\Http\Controllers\AmoCRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\Oauth\CallbackRequest;
use App\Services\AmoCRM\Core\Facades\Amo;
use App\Services\AmoCRM\Core\Oauth\FileOauthService;
use Illuminate\Http\JsonResponse;

class DctAuthController extends Controller
{
    public function __construct(private readonly FileOauthService $saveAccessTokenService)
    {
    }

    public function callback(CallbackRequest $request): JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");

        $accessToken = Amo::admin()->authenticator()->exchangeCodeWithAccessToken($code);

        $this->saveAccessTokenService->saveOAuthToken($accessToken, $domain);

        do_log("amocrm/admin/auth")->info("$domain Успешно авторизован.");

        return response()->json(['success' => true]);
    }
}