<?php

namespace App\Services\AmoCRM\Entities\Source;

use AmoCRM\Models\SourceModel;

interface SourceApiInterface
{
    public function create(SourceModel $model): SourceModel;

    public function update(SourceModel $model): SourceModel;
}