<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
        return [
            'title'        => ['required', 'string', 'max:255'],
            'announcement' => ['required', 'string', 'max:500'],
            'body'         => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'author_id'    => ['required', 'exists:authors,id'],
            'rubric_ids'   => ['required', 'array', 'min:1'],
            'rubric_ids.*' => ['integer', 'exists:rubrics,id'],
        ];
    }
}
