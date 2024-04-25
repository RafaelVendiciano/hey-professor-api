<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Support\Facades\Validator;

class MyQuestionsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) {
        
        $user_id = auth()->user()->id;

        $status = request()->status;

        Validator::validate(
            ['status' => $status],
            ['status' => ['required', 'in:draft,published,archived']]
        );
        
        $questions = Question::query()
        ->where('user_id', $user_id)
        ->where('status',  $status)
        ->get();

        return QuestionResource::collection($questions);
    }
}
