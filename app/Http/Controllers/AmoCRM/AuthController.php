<?php

namespace App\Http\Controllers\AmoCRM;

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

    public function callback(CallbackRequest $request): RedirectResponse|JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");
        $user = User::getByDomainOrCreate($domain);

        $authenticator = Amo::domain($user->domain)->authenticator();
        $accessToken = $authenticator->exchangeCodeWithAccessToken($code);
        Amo::oauth()->saveOAuthToken($accessToken, $user->domain);

        do_log("amocrm/auth-callback")->info("{$user->domain} was authenticated successfully");

        if (env('APP_ENV') === 'local') {
            return response()->json(['status' => 'success']);
        }

        return redirect()->back();
    }
}