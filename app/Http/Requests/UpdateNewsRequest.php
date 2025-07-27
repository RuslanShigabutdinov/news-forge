<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ImmutablePublishedAt;

class UpdateNewsRequest extends FormRequest
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
        $news = $this->route('news');
        return [
            'title'        => ['sometimes', 'string', 'max:255'],
            'announcement' => ['sometimes', 'string', 'max:500'],
            'body'         => ['sometimes', 'string'],
            'published_at' => ['sometimes', 'nullable', 'date',
                new ImmutablePublishedAt($news),
        ],
            'author_id'    => ['sometimes', 'exists:authors,id'],
            'rubric_ids'   => ['sometimes', 'array', 'min:1'],
            'rubric_ids.*' => ['integer', 'exists:rubrics,id'],
        ];
    }
}
