<?php

namespace BildVitta\Hub\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;

class EnvironmentVariableEncryptCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $this->encrypter()->decrypt($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return $this->encrypter()->encrypt($value);
    }

    private function encrypter(): Encrypter
    {
        $appKey = config('hub.environment_variable_key');

        $key = base64_decode(substr($appKey, 7));

        return new Encrypter($key, 'AES-256-CBC');
    }
}
