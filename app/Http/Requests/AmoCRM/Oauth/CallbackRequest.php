<?php

namespace App\Http\Requests\AmoCRM\Oauth;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'referer' => ['required', 'string'],
            'state'=> ['nullable', 'string'],
        ];
    }
}
