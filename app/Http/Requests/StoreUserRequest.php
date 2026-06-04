<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        $allowedRoles = $this->user()->hasRole('superadmin')
            ? ['superadmin', 'admin_tu', 'pimpinan', 'staf']
            : ['admin_tu', 'pimpinan', 'staf'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'opd_id' => $this->user()->hasRole('superadmin')
                ? ['nullable', 'exists:opds,id']
                : ['prohibited'],
        ];
    }
}
