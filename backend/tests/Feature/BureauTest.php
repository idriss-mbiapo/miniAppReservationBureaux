<?php

namespace Tests\Feature;

use App\Models\Bureau;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BureauTest extends TestCase
{
    /**
     * Tester la recuperation de la liste des bureaux
     */

     use RefreshDatabase;
    public function test_list_bureau()
    {
        Bureau::factory()->create(['nom' => 'Bureau 1', 'emplacement' => 'étage 1']);

        $response = $this->getJson('/api/bureaus');

        $response->assertStatus(200)->assertJsonFragment(['nom' => 'Bureau 1']);
    }
}
