<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Rubric;
use App\Rules\NotSelfOrDescendant;

class UpdateRubricRequest extends FormRequest
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
        'name'      => ['sometimes', 'string', 'max:255'],
        'parent_id' => [
    'sometimes', 'nullable', 'exists:rubrics,id',
    new NotSelfOrDescendant($this->route('rubric')),
],
    ];
}

}
