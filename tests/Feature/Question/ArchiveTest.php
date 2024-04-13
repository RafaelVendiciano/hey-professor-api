<?php

    use App\Rules\WithQuestionMark;
    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\assertDatabaseMissing;
    use function Pest\Laravel\assertSoftDeleted;
    use function Pest\Laravel\assertNotSoftDeleted;
    use function Pest\Laravel\postJson;
    use function Pest\Laravel\deleteJson;
    use function PHPUnit\Framework\assertJson;

    use Laravel\Sanctum\Sanctum;

    it('should be able to archive a question', function(){

            //$this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id]);
            Sanctum::actingAs($user);

            deleteJson(route('questions.archive', $question))->assertNoContent();

            assertSoftDeleted('questions', [
                'id' => $question->id
            ]);
    });

    test('only the creator of a question can archive it', function(){

       //  $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user1->id]);
        Sanctum::actingAs($user2);

        deleteJson(route('questions.archive', $question))->assertForbidden();

        assertNotSoftDeleted('questions', [
            'id' => $question->id
        ]);
});

?>