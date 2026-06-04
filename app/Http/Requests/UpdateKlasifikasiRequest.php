<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKlasifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('klasifikasi'));
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', Rule::unique('klasifikasis', 'kode')->ignore($this->route('klasifikasi'))],
            'nama' => ['required', 'string', 'max:255'],
        ];
    }
}
