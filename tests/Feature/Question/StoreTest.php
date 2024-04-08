<?php

    use App\Rules\WithQuestionMark;
    use App\Models\User;
    use App\Models\Question;
    use function Pest\Laravel\assertDatabaseCount;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertJson;

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

    test('with the creation of a question, we need to make sure it creates with status _draft_', function(){
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

        test('question::min characters should be 10', function(){
            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(route('questions.store', [
                'question' => 'min?'
            ]))->assertJsonValidationErrors([
                'question' => 'The question field must be at least 10 characters.'
            ]);
        });

        test('question::should be unique', function(){
            // $this->withoutExceptionHandling();
            $user = User::factory()->create();
            Question::create([
                'question' => 'Lorem ipsum teste?', 
                'user_id' => $user->id, 
                'status' => 'draft']);
            Sanctum::actingAs($user);

            postJson(route('questions.store', [
                'question' => 'Lorem ipsum teste?'
            ]))->assertJsonValidationErrors([
                'question' => 'The question has already been taken.'
            ]);
        });
    });

    test('after creating we should return a status 201 with the created question', function() {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $request = postJson(route('questions.store', [
            'question' => 'Lorem ipsum teste?'
        ]))->assertCreated();

        $question = Question::latest()->first();
        
        $request->assertJson([
            'data' => [
                'id' => $question->id,
                'question' => $question->question,
                'status' => $question->status,
                'created_at' => $question->created_at->format('Y-m-d'),
                'updated_at' => $question->updated_at->format('Y-m-d')
            ]]);
    });
?>