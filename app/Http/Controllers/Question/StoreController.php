<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request){
        $question = Question::create([
            'question' => $request->question,
            'user_id' => user()->id,
            'status' => 'draft'
        ]);

        return QuestionResource::make($question);

        // return response([
        //     'data' => [
        //         'id' => $question->id,
        //         'question' => $question->question,
        //         'status' => $question->status,
        //         'created_at' => $question->created_at->format('Y-m-d'),
        //         'updated_at' => $question->updated_at->format('Y-m-d')]
        //     ], Response::HTTP_CREATED);
    }
}
 