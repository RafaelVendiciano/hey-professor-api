<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Database\Eloquent\Builder;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) {

        $search = request()->q;

        $questions = Question::query()
        // ->where('status', '=', 'published')
        ->published()
        //->when($search, fn(Builder $query) => $query->where('question', 'like', "%{$search}%"))
        ->search($search)
        ->get();

        return QuestionResource::collection($questions);

    }
}
