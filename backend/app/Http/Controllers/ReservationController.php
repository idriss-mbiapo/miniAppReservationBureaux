<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/reservation",
     *     tags={"Réservations"},
     *     summary="Créer une réservation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"bureau_id", "date_debut", "date_fin"},
     *             @OA\Property(property="bureau_id", type="integer", example=1),
     *             @OA\Property(property="date_debut", type="string", format="date-time", example="2025-07-18T09:00:00"),
     *             @OA\Property(property="date_fin", type="string", format="date-time", example="2025-07-18T10:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Réservation créée avec succès"),
     *     @OA\Response(response=409, description="Conflit de réservation"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
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

            $reservationConflict = Reservation::where('bureau_id', $bureauId)
                ->where(function ($query) use ($dateDebut, $dateFin) {
                    $query->where('date_debut', '<', $dateFin)
                          ->where('date_fin', '>', $dateDebut);
                })
                ->exists();

            if ($reservationConflict) {
                return response()->json([
                    'error' => 'Ce bureau est déjà réservé durant cette plage horaire.'
                ], 409);
            }

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
     * @OA\Get(
     *     path="/users/{id}/reservations",
     *     tags={"Liste Réservations"},
     *     summary="Récupérer les réservations de l'utilisateur authentifié",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des réservations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="bureau_id", type="integer", example=1),
     *                 @OA\Property(property="date_debut", type="string", format="date-time", example="2025-06-17 09:00:00"),
     *                 @OA\Property(property="date_fin", type="string", format="date-time", example="2025-07-17 17:00:00"),
     *                 @OA\Property(
     *                     property="bureau",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nom", type="string", example="Bureau 1"),
     *                     @OA\Property(property="emplacement", type="string", example="Étage 1"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Aucune réservation trouvée")
     * )
     */
    public function fetchUserReservations($id)
    {
        try {
            $reservations = Reservation::with('bureau')
                ->where('user_id', $id)
                ->get();

            if ($reservations->isEmpty()) {
                return response()->json(['message' => 'Aucune réservation trouvée pour cet utilisateur'], 404);
            }

            return response()->json($reservations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des réservations'], 500);
        }
    }
}
