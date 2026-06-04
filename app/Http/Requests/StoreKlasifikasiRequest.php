<?php

namespace App\Http\Requests;

use App\Models\Klasifikasi;
use Illuminate\Foundation\Http\FormRequest;

class StoreKlasifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Klasifikasi::class);
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', 'unique:klasifikasis,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ];
    }
}
