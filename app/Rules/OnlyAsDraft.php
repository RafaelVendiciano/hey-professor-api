<?php

namespace App\Rules;

use Closure;
use App\Models\Question;
use Illuminate\Contracts\Validation\ValidationRule;

class OnlyAsDraft implements ValidationRule {

    public function __construct(private readonly Question $question) {
        
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->question->status != 'draft') {
            $fail('only draft questions can be edited');
        }
    }
}
