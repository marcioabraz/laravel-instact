<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /**
     * Entrar na página inicial e ver a frase instact
     * @return void
     */
    public function testOpenIndexAndSeeInstact()
    {
        $response = $this->get('/');
        $response->assertSee('Instact');
    }
    /**
     * Entrar na rota inicial e não ver a palavra dashboard
     */
    public function testOpenIndexDontSeeDashBoard()
    {
        $response = $this->get('/');
        $response->assertDontSee('dashboard');
    }
    /**
     * Tentar entrar na rota dashboard sem autenticação e retornar erro
     */
    public function testOpenDashboardShouldReturnErrorWithoutAuth()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/');

    
    }
    /**
     * Entrar na rota dashboard com autenticação
     */
    public function testShouldOpenDashboardWithauth()
    {
        $user = User::factory()->create();
        $this-> actingAs($user);
        $response = $this->get('/dashboard');
        $response->assertok();
        $response->assertSee('Dashboard');

    }
}
