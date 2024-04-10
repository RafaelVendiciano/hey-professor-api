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

        test('question::ending-with-question-mark', function(){

            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id]);
            Sanctum::actingAs($user);

            putJson(route('questions.update', $question), [
                'question' => 'testing without question mark'
            ])->assertJsonValidationErrors([
                'question' => 'are you sure that is a question? It is missing a question mark'
            ]);
        });

        test('question::min characters should be 10', function(){

            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::factory()->create(['user_id'=> $user->id]);
            Sanctum::actingAs($user);

            putJson(route('questions.update', $question), [
                'question' => 'test'
            ])->assertJsonValidationErrors([
                'question' => 'The question field must be at least 10 characters.'
            ]);
        });

        test('question::should be unique only if id is different', function(){

            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            $question = Question::create([
                'user_id' => $user->id,
                'status' => 'draft',
                'question' => 'Lorem ipsum test?'
            ]);
            Sanctum::actingAs($user);

            putJson(route('questions.update', $question), [
                'question' => 'Lorem ipsum test?'
            ])->assertOk();
        });

    });

?>