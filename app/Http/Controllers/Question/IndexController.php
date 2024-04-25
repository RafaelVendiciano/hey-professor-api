<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) {

        $questions = Question::query()
        ->where('status', '=', 'published')
        ->get();

        return QuestionResource::collection($questions);

    }
}
