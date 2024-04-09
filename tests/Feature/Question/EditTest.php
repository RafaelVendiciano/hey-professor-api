<?php

    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\putJson;
    use Laravel\Sanctum\Sanctum;

    it('should be able to update a question', function(){
        // $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id'=> $user->id]);
        Sanctum::actingAs($user);
            
        putJson(route('questions.update', $question), [
            'question' => 'Updating question?'
        ])->assertOk();

        
        assertDatabaseHas('questions', [
            'id' => $question->id,
            'question' => 'Updating question?',
            'user_id' => $user->id
        ]);
    });

    describe('validation rules', function() {
        test('question::required', function(){

            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id]);
            Sanctum::actingAs($user);

            putJson(route('questions.update', $question), [
                'question' => ''
            ])->assertJsonValidationErrors([
                'question' => 'required'
            ]);

        });

    });

?>