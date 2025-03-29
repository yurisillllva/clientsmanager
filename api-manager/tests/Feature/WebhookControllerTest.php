<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebhookControllerTest extends TestCase
{

    use RefreshDatabase;
    
    public function test_handleClient_cria_cliente_com_sucesso()
    {
        $user = \App\Models\User::factory()->create();

        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'photo' => 'https://example.com/photo.jpg',
            'age' => 30,
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/webhook/client', $data);

        $response->assertStatus(201)
            ->assertJson(['response' => true]);

        $this->assertDatabaseHas('clients', [
            'email' => 'john.doe@example.com',
            'user_id' => $user->id
        ]);
    }

    public function test_handleClient_retorna_erro_validacao_campos_obrigatorios()
    {
        $data = [
            // Campos obrigatórios faltando
        ];

        $response = $this->postJson('/api/webhook/client', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'messages' => [
                    'name',
                    'email',
                    'phone'
                ]
            ]);
    }

    public function test_handleClient_retorna_erro_validacao_campo_invalido()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'state' => 'New York',
            'photo' => 'not-a-url',
            'age' => -5
        ];

        $response = $this->postJson('/api/webhook/client', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
                'messages' => [
                    'state',
                    'photo',
                    'age'
                ]
            ]);
    }
}
