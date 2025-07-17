<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non authentifié'], 401);
            }
    
            $bureauId = $request->bureau_id;
            $dateDebut = $request->date_debut;
            $dateFin = $request->date_fin;
    
            // Vérification de conflit de réservation
            $reservationConflict = Reservation::where('bureau_id', $bureauId)
                ->where(function ($query) use ($dateDebut, $dateFin) {
                    $query->where('date_debut', '<', $dateFin)
                          ->where('date_fin', '>', $dateDebut);
                })
                ->exists();
    
            if ($reservationConflict) {
                return response()->json([
                    'error' => 'Ce bureau est déjà réservé durant cette plage horaire.'
                ], 409); // 409 Conflict
            }
    
            // Vérification qu'un même utilisateur ne réserve pas plusieurs fois le même bureau en même temps
            $userConflict = Reservation::where('bureau_id', $bureauId)
                ->where('user_id', $user->id)
                ->where(function ($query) use ($dateDebut, $dateFin) {
                    $query->where('date_debut', '<', $dateFin)
                          ->where('date_fin', '>', $dateDebut);
                })
                ->exists();
    
            if ($userConflict) {
                return response()->json([
                    'error' => 'Vous avez déjà une réservation pour ce bureau durant cette plage horaire.'
                ], 409);
            }
    
            // Création de la réservation si pas de conflit
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'bureau_id' => $bureauId,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
            ]);
    
            return response()->json([
                'message' => 'Réservation créée avec succès',
                'reservation' => $reservation
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la réservation', 'message' => $e->getMessage()], 500);
        }
    }
    

    /**
      * Recuperation des reservations d'un utilisateur
     */
     public function fetchUserReservations($id)
     {
        try {

            $reservations = Reservation::with('bureau')
                ->where('user_id', $id)
                ->paginate(2);
        
            if ($reservations->isEmpty()) {
                return response()->json(['message' => 'Aucune réservation trouvée pour cet utilisateur'], 404);
            }

            return response()->json($reservations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des réservations'], 500);
        }
     }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
