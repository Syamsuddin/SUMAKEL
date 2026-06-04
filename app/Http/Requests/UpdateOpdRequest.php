<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOpdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('opd'));
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:50', Rule::unique('opds', 'kode')->ignore($this->route('opd'))],
            'nama' => ['required', 'string', 'max:255'],
            'format_nomor' => ['nullable', 'string', 'max:255'],
            'is_aktif' => ['boolean'],
        ];
    }
}
