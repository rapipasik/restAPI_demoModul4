<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $mediaId = $this->route('medium')?->id ?? $this->route('media')?->id;

        return [
            'title' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => [
                $isPatch ? 'sometimes' : 'required',
                Rule::in(['Anime', 'Film']),
            ],
            'genre' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'episodes' => [
                'nullable',
                'required_if:type,Anime',
                'integer',
                'min:1',
            ],
            'duration_minutes' => [
                'nullable',
                'required_if:type,Film',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi.',
            'title.unique' => 'Judul media sudah terdaftar di FlipPlay.',
            'type.required' => 'Jenis media wajib diisi.',
            'type.in' => 'Jenis media harus bernilai Anime atau Film.',
            'genre.required' => 'Genre media wajib diisi.',
            'episodes.required_if' => 'Jumlah episode wajib diisi untuk jenis Anime.',
            'episodes.min' => 'Jumlah episode harus lebih besar dari 0.',
            'duration_minutes.required_if' => 'Durasi (menit) wajib diisi untuk jenis Film.',
            'duration_minutes.min' => 'Durasi harus lebih besar dari 0 menit.',
        ];
    }
}
