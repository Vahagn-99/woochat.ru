<?php

namespace App\Enums;

enum InstanceStatus: string
{
    // Инстанс не авторизован.
    case NOT_AUTHORIZED = 'notAuthorized';
    //Инстанс авторизован.
    case AUTHORIZED = 'authorized';
    // Инстанс забанен
    case BLOCKED = 'blocked';
    // Инстанс в процессе запуска (сервисный режим).
    // Происходит перезагрузка инстанса, сервера или инстанс в режиме обслуживания.
    // Может потребоваться до 5 минут для перехода состояния инстанса в значение authorized
    case STARTING = 'starting';
    // На инстансе частично или полностью приостановлена отправка сообщений из-за спамерской активности.
    // Сообщения отправленные после получения статуса хранятся в очереди к отправке 24 часа.
    // Для продолжения работы инстанса требуется сделать перезагрузку инстанса
    case YELLOW_CARD = 'yellowCard';

    public function isAuthorized(): bool
    {
        return $this === self::AUTHORIZED;
    }

    public function isStarting(): bool
    {
        return $this === self::STARTING;
    }
}