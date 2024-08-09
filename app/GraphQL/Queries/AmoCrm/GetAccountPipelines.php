<?php declare(strict_types=1);

namespace App\GraphQL\Queries\AmoCrm;

use App\Services\AmoCRM\Core\Facades\Amo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

final readonly class GetAccountPipelines
{
    /**
     * @param null $_
     * @param array{} $args
     * @return array
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function __invoke(null $_, array $args): array
    {
        // amocrm api call
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = Amo::api($user->domain)->pipelines()->get(with: ['leads'])->toArray();

        return Arr::map($data, function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'statuses' => Arr::map($item['statuses'], fn($item) => ['id' => $item['id'], 'name' => $item['name']]),
            ];
        });
    }
}
