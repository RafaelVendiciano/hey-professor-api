<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Question $question, string $vote)
    {
        // como to puxando de question, não preciso enviar qual o question_id
        $question->votes()->create([
            'user_id' => user()->id,
            $vote => 1
        ]);

        return response()->noContent();
    }
}
