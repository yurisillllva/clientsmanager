<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_com_credenciais_validas_retorna_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha123')
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'senha123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type'
                 ]);
    }

    public function test_login_com_credenciais_invalidas_retorna_erro()
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha123')
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'senhaErrada'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Credenciais inválidas'
                 ]);
    }

    public function test_login_falha_ao_gerar_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha123')
        ]);

        JWTAuth::shouldReceive('attempt')->andThrow(new \Tymon\JWTAuth\Exceptions\JWTException());

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'senha123'
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'error' => 'Não foi possível criar o token'
                 ]);
    }
}
