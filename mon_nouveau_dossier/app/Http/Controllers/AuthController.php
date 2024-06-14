<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Chauffeur;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    //connexion admin
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $credentials = $request->only("email", "password");
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Connexion réussie.',
                'user' => $user, 
                'token' => $token,
            ], 200);
        }

        return response()->json(['error' => 'Connexion échouée.'], 401);
    }
    //inscription admin
    function register(Request $request){
        $request->validate([
            "utilisateur_nom" => "required",
            "utilisateur_prenom" => "required",
            "email" => "required|email",
            "utilisateur_adresse" => "required",
            "utilisateur_tel" => "required",
            "password" => "required|confirmed",
        ]);
    
        $user = new User();
        $user->utilisateur_nom = $request->utilisateur_nom;
        $user->utilisateur_prenom = $request->utilisateur_prenom;
        $user->email = $request->email;
        $user->utilisateur_tel = $request->utilisateur_tel;
        $user->utilisateur_adresse = $request->utilisateur_adresse;
        $user->password = Hash::make($request->password);
    
        if ($user->save()){
            return response()->json(['status' => 'success', 'message' => 'Compte créé avec succès.'], 201);
        }
    
        return response()->json(['error' => 'Échec de la création du compte.'], 400);
    }


    //connexion chauffeur
    public function loginChauffeur(Request $request)
    {
        $request->validate([
            "chauffeur_email" => "required|email",
            "password" => "required",
        ]);
    
        if (Auth::guard('chauffeur')->attempt(['chauffeur_email' => $request->chauffeur_email, 'password' => $request->password])) {
            $chauffeur = Auth::guard('chauffeur')->user();
            $token = $chauffeur->createToken('auth_token', ['*'])->plainTextToken;
    
            return response()->json([
                'status' => 'success',
                'message' => 'Connexion réussie.',
                'chauffeur' => [
                    'id' => $chauffeur->id,
                    'chauffeur_nom' => $chauffeur->chauffeur_nom,
                    'chauffeur_prenom' => $chauffeur->chauffeur_prenom,
                    'chauffeur_email' => $chauffeur->chauffeur_email
                ],
                'token' => $token,
            ], 200);
        }
    
        return response()->json(['error' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'], 401);
    }

    //inscription chauffeur
    function registerChauffeur(Request $request){
        $request->validate([
            "chauffeur_nom" => "required",
            "chauffeur_prenom" => "required",
            "chauffeur_email" => "required|email",
            "chauffeur_adresse" => "required",
            "chauffeur_tel" => "required",
            "password" => "required|confirmed",
        ]);

        $chauffeur = new Chauffeur();
        $chauffeur->chauffeur_nom = $request->chauffeur_nom;
        $chauffeur->chauffeur_prenom = $request->chauffeur_prenom;
        $chauffeur->chauffeur_email = $request->chauffeur_email;
        $chauffeur->chauffeur_tel = $request->chauffeur_tel;
        $chauffeur->chauffeur_adresse = $request->chauffeur_adresse;
        $chauffeur->chauffeur_statut = "";
        $chauffeur->password = Hash::make($request->password);

        if ($chauffeur->save()){
            return response()->json(['status' => 'success', 'message' => 'Compte chauffeur créé avec succès.'], 201);
        }

        return response()->json(['error' => 'Échec de la création du compte.'], 400);
    }

}
