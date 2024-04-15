<?php

    use App\Rules\WithQuestionMark;
    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\assertDatabaseMissing;
    use function Pest\Laravel\assertSoftDeleted;
    use function Pest\Laravel\assertNotSoftDeleted;
    use function Pest\Laravel\putJson;
    use function PHPUnit\Framework\assertJson;

    use Laravel\Sanctum\Sanctum;

    it('should be able to publish a question', function(){

            //$this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id, 'status' => 'draft']);
            Sanctum::actingAs($user);

            putJson(route('questions.publish', $question))->assertNoContent();

            assertDatabaseHas('questions', [
                'id' => $question->id,
                'status' => 'published'
            ]);
    });

    test('only the creator of a question can archive it', function(){

       //  $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user1->id, 'status' => 'draft']);
        Sanctum::actingAs($user2);

        putJson(route('questions.publish', $question))->assertForbidden();

        assertDatabaseHas('questions', [
            'id' => $question->id,
            'status' => 'draft'
        ]);
    });

    it('should only publish when the question is draft', function(){

        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user->id, 'status' => 'teste']);

        Sanctum::actingAs($user);

        putJson(route('questions.publish', $question))->assertNotFound();

        assertDatabaseHas('questions', [
            'id' => $question->id,
            'status' => 'teste'
        ]);
    });

?>