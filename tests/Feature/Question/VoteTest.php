<?php

    use App\Models\User;
    use App\Models\Vote;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use Laravel\Sanctum\Sanctum;

    it('should be able to like a question', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        $question = Question::factory()->published()->create();
    
        postJson(
            route('questions.vote', [
                'question' => $question,
                'vote'     => 'like',
            ])
        )->assertNoContent();
    
        expect($question->votes)
            ->toHaveCount(1);
    
        assertDatabaseHas('votes', [
            'question_id' => $question->id,
            'user_id'     => $user->id,
            'like'        => 1,
        ]);
    
    });
    
    it('should be able to dislike a question', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        $question = Question::factory()->published()->create();
    
        postJson(
            route('questions.vote', [
                'question' => $question,
                'vote'     => 'dislike',
            ])
        )->assertNoContent();
    
        expect($question->votes)
            ->toHaveCount(1);
    
        assertDatabaseHas('votes', [
            'question_id' => $question->id,
            'user_id'     => $user->id,
            'dislike'      => 1,
        ]);
    });
    
    it('should guarantee that only the words like and dislike are been used to vote', function ($vote, $status) {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        $question = Question::factory()->published()->create();
    
        postJson(
            route('questions.vote', [
                'question' => $question,
                'vote'     => $vote,
            ])
        )->assertStatus($status);
    })->with([
        'like'           => ['like', 204],
        'dislike'         => ['dislike', 204],
        'something-else' => ['something-else', 422],
    ]);
    
    it('should make sure that when i set like to true, the dislike is set to false', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    
        $question = Question::factory()->published()->create();
        $question->votes()->create(['user_id' => $user->id, 'dislike' => true]);
    
        postJson(route('questions.vote', ['question' => $question, 'vote' => 'like']))->assertNoContent();
    
        // @phpstan-ignore-next-line
        expect($question->votes)->toHaveCount(1)
            ->and($question->votes()->first())->like->toBe(1)->dislike->toBe(0);
    });


?>