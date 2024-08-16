<?php

namespace App\Http\Controllers\AmoCRM;

use App\Exceptions\AmoChat\UserNotFoundException;
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
     * @throws \App\Exceptions\AmoChat\UserNotFoundException
     */
    public function callback(CallbackRequest $request): JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");
        $user = User::getByDomainOrCreate($domain);

        do_log("amocrm/auth-callback")->notice("$domain was triggered");

        if (! $user) {
            throw UserNotFoundException::byDomain($domain);
        }

        $authenticator = Amo::domain($domain)->authenticator();

        $accessToken = $authenticator->exchangeCodeWithAccessToken($code);

        Amo::oauth()->saveOAuthToken($accessToken, $domain);

        do_log("amocrm/auth-callback")->info("$domain was authenticated successfully");

        return response()->json(['success' => true]);
    }
}