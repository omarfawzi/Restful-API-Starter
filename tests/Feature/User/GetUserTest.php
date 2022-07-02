<?php

namespace Tests\Feature\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetUser(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_token');

        $this->get("api/v1/users/$user->id",['Authorization' => "Bearer $token->plainTextToken"])->assertSuccessful()->assertJson(
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        );
    }
}