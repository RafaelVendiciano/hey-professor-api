<?php

    use Illuminate\Support\Facades\Hash;
    use App\Models\User;

    use function Pest\Laravel\assertAuthenticatedAs;
    use function Pest\Laravel\assertDatabaseHas;
    use function Pest\Laravel\assertGuest;
    use function Pest\Laravel\actingAs;
    use function Pest\Laravel\postJson;
    use function PHPUnit\Framework\assertJson;
    use function PHPUnit\Framework\assertTrue;
    use Laravel\Sanctum\Sanctum;
    

    it('should be able to logout', function () {

        $user = User::factory()->create(['email' => 'test@test.com', 'password' => Hash::make('password')]);
        actingAs($user);

        postJson(route('logout'))->assertNoContent();

        assertGuest();
    });


?>