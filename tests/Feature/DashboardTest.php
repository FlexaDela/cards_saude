<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_home(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_user_logged_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this-> actingAs($user)->get('painel-dona-bebeth/dashboard');

        $response->assertStatus(200);
    }

}
