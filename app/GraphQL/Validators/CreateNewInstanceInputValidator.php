<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateNewInstanceInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', Rule::exists('users', 'id')],
            'name' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }
}
