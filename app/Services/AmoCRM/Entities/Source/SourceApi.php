<?php

namespace App\Services\AmoCRM\Entities\Source;

use AmoCRM\Models\SourceModel;
use App\Services\AmoCRM\Core\Facades\Amo;

class SourceApi implements SourceApiInterface
{
    /**
     * @param \AmoCRM\Models\SourceModel $model
     * @return \AmoCRM\Models\SourceModel
     *
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function create(SourceModel $model): SourceModel
    {
        return Amo::api()->sources()->addOne($model);
    }

    /**
     * @param \AmoCRM\Models\SourceModel $model
     * @return \AmoCRM\Models\SourceModel
     *
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     * @throws \AmoCRM\Exceptions\AmoCRMoAuthApiException
     */
    public function update(SourceModel $model): SourceModel
    {
        return Amo::api()->sources()->updateOne($model);
    }
}