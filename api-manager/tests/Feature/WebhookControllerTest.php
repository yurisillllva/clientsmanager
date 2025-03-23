<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;

class WebhookControllerTest extends TestCase
{

    public function test_handleClient_cria_cliente_com_sucesso()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'photo' => 'https://example.com/photo.jpg',
            'age' => 30
        ];

        $response = $this->postJson('/api/webhook/client', $data);

        $response->assertStatus(201) 
            ->assertJson(['response' => true]);

        $this->assertDatabaseHas('clients', [
            'email' => 'john.doe@example.com'
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
            'state' => 'New York', // Estado inválido (deve ter 2 caracteres)
            'photo' => 'not-a-url', // URL inválida
            'age' => -5 // Idade inválida
        ];

        $response = $this->postJson('/api/webhook/client', $data);

        $response->assertStatus(422) // 422 Unprocessable Entity
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