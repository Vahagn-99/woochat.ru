<?php

namespace App\Http\Controllers\AmoCRM;

use App\DTO\AmoAccountInfoDTO;
use App\Events\Messengers\AmoChat\ChannelRequested;
use App\Events\Widget\WidgetInstalled;
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
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     */
    public function callback(CallbackRequest $request): RedirectResponse|JsonResponse
    {
        $domain = $request->validated("referer");
        $code = $request->validated("code");
        $user = User::getByDomainOrCreate($domain);

        $authenticator = Amo::domain($user->domain)->authenticator();

        $accessToken = $authenticator->exchangeCodeWithAccessToken($code);

        Amo::oauth()->saveOAuthToken($accessToken, $user->domain);

        do_log("amocrm/auth")->info("{$user->domain} was authenticated successfully");

        $account = Amo::api()->account()->getCurrent(['amojo_id', 'datetime_settings']);

        $users = Amo::api()->users()->get();

        $currentUser = $users->getBy('id', $account->getCurrentUserId());

        $user->amojo_id = $account->getAmojoId();
        $user->email = $currentUser->getEmail();
        $user->save();

        $info = new AmoAccountInfoDTO($account->getId(), $account->getSubdomain(), $account->getName(), $users->count(), $account->getDatetimeSettings()->getTimezone());

        WidgetInstalled::dispatchIf(! $user->wasInformed(), $user, $info);

        ChannelRequested::dispatch($user);

        if (env('APP_ENV') === 'local') {
            return response()->json(['status' => 'success']);
        }

        return redirect()->back();
    }
}