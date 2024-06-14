<?php

namespace App\Http\Controllers;
use App\Models\Chauffeur;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    //Fonction qui envoie les données d'unb chauffeur en particulier
    public function getChauffeurDetails($id)
    {
        $chauffeur = Chauffeur::with('missions.bus')->findOrFail($id);
        return response()->json(['chauffeur' => $chauffeur], 200);
    }
    
    //Fonction pour supprimer un chauffeur de la BD
    public function deleteChauffeur($id)
    {
        $chauffeur = Chauffeur::findOrFail($id);
        $chauffeur->delete();
        return response()->json(['message' => 'Chauffeur supprimé avec succès'], 200);
    }
    
    
}
