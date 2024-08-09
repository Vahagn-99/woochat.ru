<?php

namespace App\Http\Controllers\AmoCRM;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmoCRM\Oauth\CallbackRequest;
use App\Models\User;
use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function auth(): RedirectResponse
    {
        return redirect(Amo::authenticator()->url());
    }

    /**
     * @throws \App\Exceptions\UserNotFoundException
     */
    public function callback(CallbackRequest $request): JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");
        $user = User::findByDomain($domain);

        do_log("amocrm/auth-callback")->notice("$domain was triggered");

        if (! $user) {
            do_log("amocrm/auth-callback")->error("$domain was not found when trying to callback");
            throw UserNotFoundException::byDomain($code);
        }

        $accessToken = Amo::authenticator()->exchangeCodeWithAccessToken($domain, $code);

        Amo::tokenizer()->saveAccessToken($domain, $accessToken);

        do_log("amocrm/auth-callback")->info("$domain was authenticated successfully");

        return response()->json(['success' => true]);
    }
}