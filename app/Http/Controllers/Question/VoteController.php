<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Question $question, string $vote)
    {
        Validator::validate(compact('vote'), [
            'vote' => ['required', 'in:like,dislike']
        ]);
        
    
        // como to puxando de question, não preciso enviar qual o question_id
        $question->votes()->updateOrCreate(
            ['user_id' => user()->id],
            [
                $vote => 1,
                $vote == 'like' ? 'dislike' : 'like' => 0
            ]
        );

        return response()->noContent();
    }
}
