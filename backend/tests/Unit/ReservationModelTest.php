<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Bureau;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\UserFactory;
use Database\Factories\BureauFactory;

class ReservationModelTest extends TestCase
{
    /**
     * tester les relations entre modèles afin de créer une réservation
     */

    use RefreshDatabase;

    public function test_reservation_belongs_to_a_user_and_a_bureau()
    {
        $user = User::factory()->create();
        $bureau = Bureau::factory()->create();

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'bureau_id' => $bureau->id,
            'date_debut' => now(),
            'date_fin' => now()->addHour(),
        ]);

        $this->assertInstanceOf(User::class, $reservation->user);
        $this->assertInstanceOf(Bureau::class, $reservation->bureau);
    }
}
