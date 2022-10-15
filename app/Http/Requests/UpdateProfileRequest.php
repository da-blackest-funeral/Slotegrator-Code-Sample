<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read ?string $email
 * @property-read ?string $phone
 * @property-read User $user
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['email'],
            'phone' => ['max:40']
        ];
    }
}
