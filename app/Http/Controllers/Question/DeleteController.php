<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Http\Requests\Question\DeleteRequest;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeleteRequest $request, Question $question)
    {
        $question->forceDelete();

        return response()->noContent();
    }
}
