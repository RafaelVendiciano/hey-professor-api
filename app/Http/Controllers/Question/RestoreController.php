<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RestoreController extends Controller {

    use AuthorizesRequests;
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id) {

        $question = Question::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $question);

        $question->restore();

        return QuestionResource::make($question);
    }
}
