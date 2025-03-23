<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class ChartControllerTest extends TestCase
{

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

    public function test_states_retorna_dados_estados()
    {
        Client::factory()->create(['user_id' => $this->user->id, 'state' => 'SP']);
        Client::factory()->create(['user_id' => $this->user->id, 'state' => 'RJ']);
        Client::factory()->create(['user_id' => $this->user->id, 'state' => 'SP']);

        $response = $this->getJson(route('charts.states'), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'data'
            ])
            ->assertJsonCount(2, 'labels')
            ->assertJsonCount(2, 'data');
    }

    public function test_states_retorna_erro_se_nao_houver_dados()
    {
        $response = $this->getJson(route('charts.states'), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Nenhum dado encontrado']);
    }

    public function test_cities_retorna_dados_cidades()
    {
        Client::factory()->create(['user_id' => $this->user->id, 'city' => 'São Paulo']);
        Client::factory()->create(['user_id' => $this->user->id, 'city' => 'Rio de Janeiro']);
        Client::factory()->create(['user_id' => $this->user->id, 'city' => 'São Paulo']);

        $response = $this->getJson(route('charts.cities'), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'data'
            ])
            ->assertJsonCount(2, 'labels')
            ->assertJsonCount(2, 'data');
    }

    public function test_cities_retorna_erro_se_nao_houver_dados()
    {
        $response = $this->getJson(route('charts.cities'), [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Nenhum dado encontrado']);
    }
}
