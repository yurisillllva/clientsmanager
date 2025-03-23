<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_index_retorna_lista_de_clientes()
    {
        Client::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('clients.index'), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_store_cria_novo_cliente()
    {
        $data = [
            'name' => 'Cliente Teste',
            'email' => 'cliente@email.com',
            'phone' => '999999999'
        ];

        $response = $this->postJson(route('clients.store'), $data, [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data']);
    }

    public function test_show_retorna_cliente_existente()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('clients.show', $client->id), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'phone',
                'city',
                'state',
                'photo',
                'age'
            ]);
    }

    public function test_show_retorna_erro_para_cliente_inexistente()
    {
        $response = $this->getJson(route('clients.show', 999), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Cliente não encontrado']);
    }

    public function test_update_altera_cliente_existente()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $data = ['name' => 'Nome Alterado'];

        $response = $this->putJson(route('clients.update', $client->id), $data, [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nome Alterado']);
    }

    public function test_update_retorna_erro_para_cliente_inexistente()
    {
        $data = ['name' => 'Nome Alterado'];

        $response = $this->putJson(route('clients.update', 999), $data, [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Cliente não encontrado']);
    }

    public function test_destroy_deleta_cliente_existente()
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('clients.destroy', $client->id), [], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(204);
    }

    public function test_destroy_retorna_erro_para_cliente_inexistente()
    {
        $response = $this->deleteJson(route('clients.destroy', 999), [], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Cliente não encontrado']);
    }
}
