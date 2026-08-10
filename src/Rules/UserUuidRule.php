<?php

namespace BildVitta\Hub\Rules;

use BildVitta\Hub\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class UserUuidRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Str::isUuid($value)) {
            $fail(__('O :attribute deve ser um UUID válido.'));
            return;
        }

        if (!User::where('uuid', $value)->exists()) {
            $fail(__('O :attribute informado é inválido.'));
        }
    }
}