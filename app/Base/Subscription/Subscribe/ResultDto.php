<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe;

use App\Models\Subscription as SubscriptionModel;
use InvalidArgumentException;
use Spatie\LaravelData\Dto;

final class ResultDto extends Dto
{
    /**
     * ResultDto constructor.
     *
     * @param \App\Base\Subscription\Subscribe\ResultStatus $status
     * @param \App\Models\Subscription|null $subscription
     * @param string|null $payment_url
     * @param string|null $error_message
     */
    public function __construct(
        public ResultStatus $status,
        public ?SubscriptionModel $subscription = null,
        public ?string $payment_url = null,
        public ?string $error_message = null,
    ) {
        if ($this->status === ResultStatus::FAIL && empty($this->error_message)) {
            throw new InvalidArgumentException("При создании объекта результата операции подписки ошибка, если статус операции неуспешный должен быть передан текст ошибки.");
        }
    }
}
