<?php

namespace App\Http\Requests;

use App\Models\Opd;
use Illuminate\Foundation\Http\FormRequest;

class StoreOpdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Opd::class);
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:50', 'unique:opds,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'format_nomor' => ['nullable', 'string', 'max:255'],
        ];
    }
}
