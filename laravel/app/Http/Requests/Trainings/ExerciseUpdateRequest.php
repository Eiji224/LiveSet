<?php

namespace App\Http\Requests\Trainings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExerciseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body_part' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'instructions' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Введите название упражнения',
            'title.max' => 'Название слишком длинное',

            'body_part.required' => 'Укажите какую часть тела развивает упражнение',
            'body_part.max' => 'Название слишком длинное',

            'instructions.required' => 'Напишите инструкцию к выполнению упражнения'
        ];
    }
}
