<?php

declare(strict_types=1);

namespace App\Base\Subscription\Subscribe;

abstract class Action
{
    /**
     * Следующее действие.
     *
     * @var \App\Base\Subscription\Subscribe\Action|null
     */
    protected ?Action $next = null;

    /**
     * Выполнение операции и получение результата.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operation_data
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    abstract protected function getResult(OperationDto $operation_data): ResultDto;

    /**
     * Выполнение операции.
     *
     * @param \App\Base\Subscription\Subscribe\OperationDto $operation_data
     * @return \App\Base\Subscription\Subscribe\ResultDto
     */
    public function execute(OperationDto $operation_data): ResultDto
    {
        $result = $this->getResult($operation_data);

        if (empty($this->next) || $result->status !== ResultStatus::SUCCESS) {
            return $result;
        }

        return $this->next->execute($operation_data);
    }

    /**
     * Установка следующего действия для выполнения.
     *
     * @param \App\Base\Subscription\Subscribe\Action $action
     * @return $this
     */
    public function setNext(Action $action): static
    {
        $this->next = $action;

        return $this;
    }
}
