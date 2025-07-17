<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use Illuminate\Http\Request;

/**
* @OA\Get(
*     path="/api/bureaus",
*     summary="Liste tous les bureaux",
*     tags={"Bureaux"},
*     @OA\Response(
*         response=200,
*         description="Liste des bureaux",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(
*                 @OA\Property(property="id", type="integer", example=1),
*                 @OA\Property(property="nom", type="string", example="Bureau 1"),
*                 @OA\Property(property="emplacement", type="string", example="étage")
*             )
*         )
*     )
* )
*/


class BureauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Bureau::all());
    }

}
