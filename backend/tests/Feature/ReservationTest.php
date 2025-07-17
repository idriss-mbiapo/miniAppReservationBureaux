<?php

namespace Tests\Feature;

use App\Models\Bureau;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    /**
     * Tester création d'une réservation et la récupération des reservations d'un utilisateur connecté
     */
    use RefreshDatabase;
     protected $user;
     protected $token;
 
     protected function setUp(): void
     {
         parent::setUp();
 
         $this->user = User::factory()->create([
             'email' => 'idriss@gmail.com',
             'password' => Hash::make('password')
         ]);
 
        
         Bureau::factory()->create(['nom' => 'Bureau 1']);
 
         $loginResponse = $this->postJson('/api/login', [
             'email' => 'idriss@gmail.com',
             'password' => 'password'
         ]);
         $this->token = $loginResponse->json('access_token');
     }
 
     /**
     * Tester la création d'une réservation pour un utilisateur connecté
     */
     public function test_create_a_reservation_for_authenticated_user()
     {
         $response = $this->postJson('/api/reservation', [
             'bureau_id' => 1,
             'date_debut' => '2025-07-16 09:00:00',
             'date_fin' => '2025-07-16 17:00:00'
         ], [
             'Authorization' => 'Bearer ' . $this->token
         ]);
 
         $response->assertStatus(201)->assertJsonFragment(['message' => 'Réservation créée avec succès']);
     }
 
     /**
     * Tester la récupération des reservations d'un utilisateur connecté
     */
     public function test_lists_user_reservations()
     {
         Reservation::create([
             'user_id' => $this->user->id,
             'bureau_id' => 1,
             'date_debut' => '2025-07-16 09:00:00',
             'date_fin' => '2025-07-16 17:00:00'
         ]);
 
         $response = $this->getJson("/api/users/{$this->user->id}/reservations", [
             'Authorization' => 'Bearer ' . $this->token
         ]);
 
         $response->assertStatus(200)->assertJsonFragment(['bureau_id' => 1]);
    }
}
