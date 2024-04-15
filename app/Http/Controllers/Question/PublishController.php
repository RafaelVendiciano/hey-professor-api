<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PublishController extends Controller
{
    use AuthorizesRequests;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Question $question)
    {
        abort_unless($question->status==='draft', 404);

        $this->authorize('publish', $question);

        $question->status = 'published';
        $question->save();

        return response()->noContent();
    }
}
