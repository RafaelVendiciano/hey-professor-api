<?php

    use App\Models\User;
    use App\Models\Vote;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use Laravel\Sanctum\Sanctum;

    it('should be able to like a question', function(){

        // $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        $question = Question::factory()->for($user)->published()->create();

        Sanctum::actingAs($user);
            
        postJson(route('questions.vote',[
            'question' => $question,
            'vote' => 'like'
        ]));

        expect($question)->votes->toHaveCount(1);

        
        assertDatabaseHas('votes', [
            'question_id' => $question->id,
            'user_id' => $user->id,
            'like' => 1
        ]);
    });

    it('should be able to dislike a question', function(){

        // $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        $question = Question::factory()->for($user)->published()->create();

        Sanctum::actingAs($user);
            
        postJson(route('questions.vote',[
            'question' => $question,
            'vote' => 'dislike'
        ]));

        expect($question)->votes->toHaveCount(1);
        
        assertDatabaseHas('votes', [
            'question_id' => $question->id,
            'user_id' => $user->id,
            'dislike' => 1
        ]);
    });


?>