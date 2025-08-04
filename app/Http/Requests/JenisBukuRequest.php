<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisBukuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $jenisBukuId = $this->route('jenis_buku');
        
        return [
            'nama_jenis' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_buku', 'nama_jenis')->ignore($jenisBukuId),
            ],
            'kode_jenis' => [
                'required',
                'string',
                'max:10',
                Rule::unique('jenis_buku', 'kode_jenis')->ignore($jenisBukuId),
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:500',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nama_jenis.required' => 'Nama jenis buku wajib diisi.',
            'nama_jenis.string' => 'Nama jenis buku harus berupa teks.',
            'nama_jenis.max' => 'Nama jenis buku maksimal 255 karakter.',
            'nama_jenis.unique' => 'Nama jenis buku sudah ada.',
            
            'kode_jenis.required' => 'Kode jenis wajib diisi.',
            'kode_jenis.string' => 'Kode jenis harus berupa teks.',
            'kode_jenis.max' => 'Kode jenis maksimal 10 karakter.',
            'kode_jenis.unique' => 'Kode jenis sudah ada.',
            
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            
            'status.required' => 'Status wajib dipilih.',
            'status.boolean' => 'Status harus berupa boolean.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'nama_jenis' => 'nama jenis buku',
            'kode_jenis' => 'kode jenis',
            'deskripsi' => 'deskripsi',
            'status' => 'status',
        ];
    }
} 