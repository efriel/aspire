<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    

    public function test_login(): void
    {
        $response = $this->post('/api/user/login', [
            'username' => 'testabc',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    public function test_register(): void
    {
        $baseUrl = Config::get('app.url') . '/api/user/register';
        $username = "testabc";
        $password = "password";

        $response = $this->post('POST', $baseUrl . '/', [
            'name' => 'TestAbc',
            'username' => $username,
            'password' => $$password,
            'email' => 'abc@email.com',
            'address' => 'TestAbc'
        ]);

        $response
            ->assertStatus(200);
            
    }

}
