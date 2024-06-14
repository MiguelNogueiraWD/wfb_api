<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Liste des chauffeurs avec filtrage
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'disponible');
        $chauffeurs = $this->getChauffeursByFilter($filter);

        return response()->json(['chauffeurs' => $chauffeurs, 'filter' => $filter], 200);
    }

    // Filtrage des chauffeurs
    private function getChauffeursByFilter($filter)
    {
        $query = Chauffeur::query();

        switch ($filter) {
            case 'en mission':
                $query->where('chauffeur_statut', 'en mission');
                break;

            case 'hors ligne':
                $query->where('chauffeur_statut', 'hors ligne');
                break;

            case 'all':
                break;

            case 'disponible':
            default:
                $query->where('chauffeur_statut', 'disponible');
                break;
        }

        return $query->get();
    }
}
