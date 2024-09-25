<?php

declare(strict_types=1);

namespace App\Services\AmoCRM\Dirty;

use App\Services\AmoCRM\Dirty\Filters\Filter;

interface PrivateApiInterface
{
    /**
     * @param \App\Services\AmoCRM\Dirty\Filters\Filter|null $filter
     * @return array|null
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function contacts(?Filter $filter = null): ?array;
}
