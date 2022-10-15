<?php

namespace App\Casts;

use App\DTO\ContactDataDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ContactDataCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $data = json_decode($value, true);

        if (is_null($value)) {
            return null;
        }

        return new ContactDataDto(
            post: $data['post'],
            email: $data['email'],
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            phone: $data['phone'] ?? '',
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  ContactDataDto  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $data = [
            'post' => $value->post,
            'email' => $value->email,
            'first_name' => $value->first_name,
            'last_name' => $value->last_name,
            'phone' => $value->phone,
        ];

        return json_encode($data);
    }
}
