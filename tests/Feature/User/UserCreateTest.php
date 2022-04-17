<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use DatabaseTransactions;

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