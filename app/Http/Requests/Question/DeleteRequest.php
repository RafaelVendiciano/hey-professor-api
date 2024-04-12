<?php

namespace App\Http\Requests\Question;

use App\Models\Question;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Question $question */
        $question = $this->route()->question;

        return Gate::allows('forceDelete', $question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
