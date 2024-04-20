<?php

    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use function PHPUnit\Framework\assertJson;
    use function PHPUnit\Framework\assertTrue;
    use Laravel\Sanctum\Sanctum;
    

    it('should be able to register in the application', function () {
        postJson(route('register'), [
            'name'               => 'John Doe',
            'email'              => 'joe@doe.com',
            'password'           => 'password'
        ])->assertOk();

        assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'joe@doe.com',
        ]);
    
        $joeDoe = User::whereEmail('joe@doe.com')->first();
    
        assertTrue(
            Hash::check('password', $joeDoe->password)
        );
    });


?>