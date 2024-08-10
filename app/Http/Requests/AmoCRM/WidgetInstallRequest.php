<?php

namespace App\Http\Requests\AmoCRM;

use App\Contracts\DTOable;
use App\DTO\AmoAccountInfoDTO;
use App\DTO\CrateNewUserDTO;
use App\DTO\WidgetInstalledDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class WidgetInstallRequest extends FormRequest implements DTOable
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric'],
            'domain' => ['required', 'string'],
            'amojo_id' => ['required', 'string'],
            "email" => ['nullable', 'string'],
            "phone" => ['nullable', 'string'],
            'users_count' => ['required', 'numeric', 'min:1'],
            "tariff" => ['required', 'string'],
            "paid_from" => ['required', 'string'],
            "paid_till" => ['required', 'string'],
            "pay_type" => ['required', 'string'],
            "timezone" => ['nullable', 'string'],
        ];
    }

    public function toDTO(): WidgetInstalledDTO
    {
        $data = $this->validated();

        $userDTO = new CrateNewUserDTO(id: Arr::get($data, "id"), amojo_id: Arr::get($data, "amojo_id"), domain: Arr::get($data, "domain"), email: Arr::get($data, "email"), phone: Arr::get($data, "phone"),);

        $infoDTO = new AmoAccountInfoDTO(id: Arr::get($data, 'id'), domain: Arr::get($data, 'domain'), users_count: Arr::get($data, 'users_count'), paid_from: Arr::get($data, 'paid_from'), paid_till: Arr::get($data, 'paid_till'), pay_type: Arr::get($data, 'pay_type'), timezone: Arr::get($data, 'timezone'), tariff: Arr::get($data, 'tariff'));

        return new WidgetInstalledDTO($userDTO, $infoDTO);
    }
}
