<?php

namespace App\Http\Requests\Question;

use App\Models\Question;
use App\Rules\OnlyAsDraft;
use App\Rules\WithQuestionMark;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $question 
*/
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Question $question */
        $question = $this->route()->question;

        return Gate::allows('update', $question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{

        /** @var Question $question */
        $question = $this->route()->question;

        return [
            'question' => ['required', 
                           new WithQuestionMark(),
                           new OnlyAsDraft($question),
                           'min:10', 
                           Rule::unique('questions')->ignoreModel($question)
                           ]
        ];
    }
}
