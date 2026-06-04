<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        $allowedRoles = $this->user()->hasRole('superadmin')
            ? ['superadmin', 'admin_tu', 'pimpinan', 'staf']
            : ['admin_tu', 'pimpinan', 'staf'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['nullable', Password::defaults()],
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'opd_id' => $this->user()->hasRole('superadmin')
                ? ['nullable', 'exists:opds,id']
                : ['prohibited'],
            'is_aktif' => ['boolean'],
        ];
    }
}
