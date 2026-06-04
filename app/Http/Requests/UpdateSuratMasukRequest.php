<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('surat_masuk'));
    }

    public function rules(): array
    {
        return [
            'klasifikasi_id' => ['required', 'exists:klasifikasis,id'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'asal_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'sifat' => ['required', Rule::in(['biasa', 'penting', 'rahasia'])],
            'lampirans' => ['nullable', 'array'],
            'lampirans.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
