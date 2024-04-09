<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Question $question) {

        $question->question = request()->question;
        $question->save();

        return QuestionResource::make($question);
    }
}
