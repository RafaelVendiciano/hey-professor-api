<?php

use App\Models\User;

use App\Models\Question;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

    it('should list only questions that the logged user has created :: published', function() {

        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $userQuestion = Question::factory()->published()->for($user, 'user')->create();
        $anotherUserQuestion = Question::factory()->published()->create();

        Sanctum::actingAs($user);

       $request =  getJson(route('my-questions', 'published'))->assertOk();

       $request->assertJsonFragment([
            'id' => $userQuestion->id,
            'question' => $userQuestion->question,
            'status' => $userQuestion->status,
            'created_by' => [
                'id' => $userQuestion->user->id,
                'name' => $userQuestion->user->name
            ],
            'created_at' => $userQuestion->created_at->format('Y-m-d h:i:s'),
            'updated_at' => $userQuestion->updated_at->format('Y-m-d h:i:s')
        ])->assertJsonMissing([
            'question' => $anotherUserQuestion->question
        ]);

    });


?>