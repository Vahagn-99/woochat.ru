<?php

namespace App\Services\AmoChat\Client;

class ChatEndpoint
{
    /**
     * Отключение канала чата в аккаунте
     *
     * @const string
     */
    const API_CONNECT_CHAT_API = "/v2/origin/custom/%s/connect";

    /**
     * Создание нового чата
     *
     * @const string
     */
    const API_CREATE_CHAT_API = "/v2/origin/custom/%s/chats";

    /**
     * @const string
     *
     * @note Отправка, редактирование или импорт сообщения.
     */
    const API_SEND_MESSAGE_API = "/v2/origin/custom/%s";

    /**
     * @const string
     *
     * @note Обновление статуса доставки сообщения
     */
    const API_SEND_MESSAGE_STATUS_API = "/v2/origin/custom/%s/%s/delivery_status";
}