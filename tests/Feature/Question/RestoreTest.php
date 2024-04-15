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
    use function Pest\Laravel\putJson;
    use function PHPUnit\Framework\assertJson;

    use Laravel\Sanctum\Sanctum;

    it('should be able to restore a question', function(){

            //$this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id]);
            $question->delete();

            assertSoftDeleted('questions', [
                'id' => $question->id
            ]);

            Sanctum::actingAs($user);

            putJson(route('questions.restore', $question))->assertSuccessful();

            assertNotSoftDeleted('questions', [
                'id' => $question->id
            ]);
    });

    test('only the creator of a question can restore it', function(){

       //  $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user1->id]);
        $question->delete();
        Sanctum::actingAs($user2);

        putJson(route('questions.restore', $question))->assertForbidden();

        assertSoftDeleted('questions', [
            'id' => $question->id
        ]);
    });

    it('should only restore when the question is deleted', function(){

        //$this->withoutExceptionHandling();
        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.restore', $question))->assertNotFound();

        assertNotSoftDeleted('questions', [
            'id' => $question->id
        ]);
    });

?>