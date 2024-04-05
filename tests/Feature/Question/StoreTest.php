<?php

    use App\Rules\WithQuestionMark;
    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use Laravel\Sanctum\Sanctum;

    it('should be able to store a new question', function(){
            // $this->withoutExceptionHandling();

            $user = User::factory()->create();
            Sanctum::actingAs($user);
            
            postJson(route('questions.store', [
                'question' => 'Lorem ipsum teste?'
            ]))->assertSuccessful();

        
            assertDatabaseCount('questions', 1);
            assertDatabaseHas('questions', [
                'question' => 'Lorem ipsum teste?',
                'user_id' => $user->id
            ]);
    });

    test('after creating a new question, i need to make sure it creates on _draft_ status', function(){
        //$this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        postJson(route('questions.store', [
            'question' => 'Lorem ipsum teste?'
        ]))->assertSuccessful();

    
        assertDatabaseCount('questions', 1);
        assertDatabaseHas('questions', [
            'question' => 'Lorem ipsum teste?',
            'user_id' => $user->id,
            'status' => 'draft'
        ]);
    });

    describe('validation rules', function() {
        test('question::required', function(){
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(route('questions.store', []))->assertJsonValidationErrors([
                'question' => 'required'
            ]);
        });

        test('question::ending-with-question-mark', function(){
            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(route('questions.store', [
                'question' => 'Question without a question mark'
            ]))->assertJsonValidationErrors([
                'question' => 'are you sure that is a question? It is missing a question mark'
            ]);
        });
    });

?>