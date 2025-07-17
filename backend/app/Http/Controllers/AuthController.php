<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @OA\Post(
 *     path="/login",
 *     summary="Authentification de l'utilisateur",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="dev@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Connexion réussie",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string"),
 *             @OA\Property(property="token_type", type="string"),
 *             @OA\Property(property="expires_in", type="integer"),
 *             @OA\Property(property="user", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Échec d'authentification"
 *     )
 * )
 */

class AuthController extends Controller
{

    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Email ou mot de passe incorrect.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Erreur lors de la création du token.'], 500);
        }

        $user = JWTAuth::user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }
}
