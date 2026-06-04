<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('surat_keluar'));
    }

    public function rules(): array
    {
        return [
            'klasifikasi_id' => ['required', 'exists:klasifikasis,id'],
            'tanggal_surat' => ['required', 'date'],
            'jenis_tujuan' => ['required', Rule::in(['eksternal', 'internal'])],
            'tujuan_eksternal' => ['required_if:jenis_tujuan,eksternal', 'nullable', 'string', 'max:255'],
            'tujuan_opd_id' => ['required_if:jenis_tujuan,internal', 'nullable', 'exists:opds,id'],
            'perihal' => ['required', 'string', 'max:255'],
            'sifat' => ['required', Rule::in(['biasa', 'penting', 'rahasia'])],
            'lampirans' => ['nullable', 'array'],
            'lampirans.*' => ['file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
