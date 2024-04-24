<?php

    use Illuminate\Support\Facades\Hash;
    use App\Models\User;

    use function Pest\Laravel\assertAuthenticatedAs;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use function PHPUnit\Framework\assertJson;
    use function PHPUnit\Framework\assertTrue;
    use Laravel\Sanctum\Sanctum;
    

    it('should be able to login', function () {

        $user = User::factory()->create(['email' => 'test@test.com', 'password' => Hash::make('password')]);

        postJson(route('login'), [
            'email' => 'test@test.com',
            'password' => 'password'
        ])->assertNoContent();

        assertAuthenticatedAs($user);

    });

    it('should check if the email and password are valie', function($email, $password) {

        User::factory()->create(['email' => 'test@test.com', 'password' => Hash::make('password')]);

        postJson(route('login'), [
            'email' => $email,
            'password' => $password
        ])->assertJsonValidationErrors([
            'email' => __('auth.failed')
        ]);

    })->with([
        'wrong email' => ['wrong@email.com', 'password'],
        'wrong password' => ['test@test.com', 'password123123'],
        'invalid email' => ['invalid-email', 'password']
    ]);

    test('required fields', function() {

        postJson(route('login'), [
            'email' => '',
            'password' => ''
        ])->assertJsonValidationErrors([
            'email' => __('validation.required', ['attribute' => 'email']),
            'password' => __('validation.required', ['attribute' => 'password'])
        ]);

    });

?>