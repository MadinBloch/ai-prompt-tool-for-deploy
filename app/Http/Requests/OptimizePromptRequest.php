<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\Attributes\StopOnFirstFailure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

#[StopOnFirstFailure]
class OptimizePromptRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prompt' => ['required', 'string', 'min:10', 'max:12000'],
            'provider' => ['nullable', 'string', 'in:openai,gemini,groq'],
            'async' => ['nullable', 'boolean'],
            'enhance_context' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'prompt' => Str::squish((string) $this->input('prompt')),
            'async' => $this->boolean('async'),
            'enhance_context' => $this->has('enhance_context')
                ? $this->boolean('enhance_context')
                : true,
        ]);
    }
}
