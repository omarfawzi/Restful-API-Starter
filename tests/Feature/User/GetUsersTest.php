<?php

namespace Tests\Feature\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetUsersTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetUsers(): void
    {
        $users = User::factory()->count(10)->create();

        $mainUser = $users[0];
        $token = $mainUser->createToken('user_token');

        $firstFiveUsers = [];
        $secondFiveUsers = [];
        foreach ($users->take(5) as $user)
        {
            $firstFiveUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }
        foreach ($users->skip(5) as $user)
        {
            $secondFiveUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }

        $cursor = $this->get('api/v1/users?limit=5',['Authorization' => "Bearer $token->plainTextToken"])
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'entities' => $firstFiveUsers
                ]
            )->json()['cursor'];

        $cursor = $this->get("api/v1/users?limit=5&cursor=$cursor",['Authorization' => "Bearer $token->plainTextToken"])
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'entities' => $secondFiveUsers
                ]
            )->json()['cursor'];

        $this->get("api/v1/users?limit=5&cursor=$cursor",['Authorization' => "Bearer $token->plainTextToken"])
            ->assertSuccessful()
            ->assertJson(
                [
                    'entities' => [],
                    'cursor' => null
                ]
            );
    }
}