<?php

namespace Tests\Feature\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @param array $payload
     * @return void
     *
     * @dataProvider getUserUpdatePayload
     */
    public function testUpdateUser(array $payload): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_token');

        $this->patchJson("api/v1/users/$user->id", $payload, ['Authorization' => "Bearer $token->plainTextToken"])
            ->assertSuccessful()
            ->assertJsonFragment($payload);
    }

    public function getUserUpdatePayload(): array
    {
        return [
            [
                [
                    'name' => 'omar',
                    'email' => 'omar@gmail.com'
                ]
            ]
        ];
    }
}