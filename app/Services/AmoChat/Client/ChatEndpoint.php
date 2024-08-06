<?php

namespace App\Services\AmoChat\Client;

class ChatEndpoint
{
    const API_CONNECT_CHAT_API = "/v2/origin/custom/%s/connect";
    const API_CREATE_CHAT_API = "/v2/origin/custom/%s/chats";
    /**
     * @const  API_SEND_MESSAGE_API
     * @note  Метод позволяет передавать входящие и исходящие сообщения (историю переписки или сообщения,
     * которые были отправлены в стороннем приложении), а так же позволяет редактировать сообщения.
     * Метод создаст сообщение и при необходимости сам чат для указанного msgid и conversation_id соответственно.
     */
    const API_SEND_MESSAGE_API = "/v2/origin/custom/%s";
}