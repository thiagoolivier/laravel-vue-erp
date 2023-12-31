<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_login(): void
    {
        $response = $this->post('api/v1/auth/login', [
            'email'=> $this->user->email,
            'password'=> 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('access_token')->etc();
            $json->whereType('access_token', 'string')->etc();
        });
    }

    public function test_me(): void
    {        
        $this->test_login();

        $response = $this->get('api/v1/auth/me');

        $response->assertStatus(200);
        
        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['id', 'name', 'email']);
            
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'email'=> 'string',
            ])->etc();

            $json->whereAll([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
        });
    }

    public function test_logout(): void
    {
        $this->test_login();

        $response = $this->post('api/v1/auth/logout');

        $response->assertStatus(200);
    }
}
