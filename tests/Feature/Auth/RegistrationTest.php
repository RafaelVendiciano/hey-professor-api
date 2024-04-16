<?php

    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\postJson;
    use function PHPUnit\Framework\assertJson;
    use function PHPUnit\Framework\assertTrue;
    use Laravel\Sanctum\Sanctum;
    

    it('should be able to register an user in the application', function(){

            //$this->withoutExceptionHandling();

            postJson(route('register'), [
                'name' => 'John Doe',
                'email' => 'joe@doe.com',
                'password' => 'password'
            ])->assertSessionHasNoErrors();

            assertDatabaseHas('users', [
                'name' => 'John Doe',
                'email' => 'joe@doe.com'
            ]);
            
            $joeDoe = User::where('email', 'joe@doe.com')->first();

            assertTrue(Hash::check('password', $joeDoe->password));

    });

?>