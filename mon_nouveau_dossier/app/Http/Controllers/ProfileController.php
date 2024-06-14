<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Chauffeur;

class ProfileController extends Controller
{
    public function index()
    {
    $chauffeurs = Chauffeur::all();
    return response()->json($chauffeurs);
    }




    // Editer le profil de l'utilisateur
    public function edit()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    // Mettre à jour le profil de l'utilisateur
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'utilisateur_nom' => 'required|string|max:255',
            'utilisateur_prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'utilisateur_tel' => 'required|string|max:255',
            'utilisateur_adresse' => 'required|string|max:255'
        ]);

        // Mise à jour des données de l'utilisateur
        $user->update($request->all());
        return response()->json(['success' => 'Profil mis à jour avec succès.', 'user' => $user], 200);
    }

    // Editer le mot de passe de l'utilisateur
    public function editpassword()
    {
        return response()->json(['message' => 'Edit password'], 200);
    }

    // Mettre à jour le mot de passe de l'utilisateur
    public function updatepassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        
        // Vérifier si le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Le mot de passe actuel n\'est pas correct.'], 400);
        }
    
        // Mise à jour du mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        return response()->json(['success' => 'Mot de passe mis à jour avec succès.'], 200);
    }

    // Editer le profil du chauffeur
    public function editChauffeur()
    {
        $chauffeur = Auth::guard('chauffeur')->user();
        return response()->json(['chauffeur' => $chauffeur], 200);
    }
    
    // Mettre à jour le profil du chauffeur
    public function updateChauffeur(Request $request)
    {

    
        $chauffeur = Auth::guard('chauffeur-api')->user();

    
        $request->validate([
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_prenom' => 'required|string|max:255',
            'chauffeur_email' => 'required|string|email|max:255|unique:chauffeurs,chauffeur_email,' . $chauffeur->id,
            'chauffeur_tel' => 'required|string|max:255',
            'chauffeur_adresse' => 'required|string|max:255'
        ]);
    
        try {
            $chauffeur->update($request->all());
            Log::info('Chauffeur profile updated successfully', ['chauffeur' => $chauffeur]);
            return response()->json(['success' => 'Profil mis à jour avec succès.', 'chauffeur' => $chauffeur], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update chauffeur profile', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Échec de la mise à jour du profil.'], 500);
        }
    }
    
    
    // Editer le mot de passe du chauffeur
    public function editPasswordChauffeur()
    {
        return response()->json(['message' => 'Edit password'], 200);
    }
    
    // Mettre à jour le mot de passe du chauffeur
    public function updatePasswordChauffeur(Request $request)
    {
        $chauffeur = Auth::guard('chauffeur')->user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $chauffeur->password)) {
            return response()->json(['error' => 'Le mot de passe actuel n\'est pas correct.'], 400);
        }
    
        $chauffeur->password = Hash::make($request->new_password);
        $chauffeur->save();
    
        return response()->json(['success' => 'Mot de passe mis à jour avec succès.'], 200);
    }
}
