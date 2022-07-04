<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @param array $payload
     * @return void
     *
     * @dataProvider getUserCreationPayload
     */
    public function testCreateUser(array $payload): void
    {
        $this->postJson('api/v1/users', $payload)->assertCreated()->assertJsonFragment([
            'name' => $payload['name'],
            'email' => $payload['email']
        ]);
    }

    /**
     * @param array $payload
     * @return void
     *
     * @dataProvider getUserCreationPayload
     */
    public function testCreateUserWithoutEmail(array $payload): void
    {
        unset($payload['email']);
        $this->postJson('api/v1/users', $payload)->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonFragment([
                'message' => 'Email is missing',
            ]);
    }

    /**
     * @param array $payload
     * @return void
     *
     * @dataProvider getUserCreationPayload
     */
    public function testCreateUserWithInvalidEmail(array $payload): void
    {
        $payload['email'] = 1234;
        $this->postJson('api/v1/users', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment([
                'errors' => ['email' => "Value expected to be 'string', 'integer' given."]
            ]);
    }

    public function getUserCreationPayload(): array
    {
        return [
            [
                [
                    'name' => 'omar',
                    'email' => 'omar@gmail.com',
                    'password' => 'AB1234'
                ]
            ]
        ];
    }
}