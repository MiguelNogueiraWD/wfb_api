<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Mission;
    use App\Models\Chauffeur;
    use App\Models\Bus;
    use App\Models\Vol;
    use Carbon\Carbon;
    use App\Models\Maintenance;
    use Illuminate\Support\Facades\Log;


        class MissionController extends Controller
    {
        // Liste des missions avec filtrage
        public function index(Request $request)
        {
            $filter = $request->input('filter', 'today');
            $missions = $this->getMissionsByFilter($filter);

            return response()->json(['missions' => $missions, 'filter' => $filter], 200);
        }

        // Création d'une mission
        public function store(Request $request)
        {
            $request->validate([
                'date' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i',
                'details_mission' => 'required|string',
                'chauffeur_id' => 'required|exists:chauffeurs,id',
                'bus_id' => 'required|exists:buses,id',
                'vol_id' => 'required|exists:vols,id',
            ]);

            $date = $request->input('date');
            $heure_debut = $request->input('heure_debut');
            $heure_fin = $request->input('heure_fin');
            $chauffeur_id = $request->input('chauffeur_id');
            $bus_id = $request->input('bus_id');

            // Vérifiez la disponibilité du chauffeur
            $chauffeurBusy = Mission::where('chauffeur_id', $chauffeur_id)
                ->whereDate('date', $date)
                ->where(function($query) use ($heure_debut, $heure_fin) {
                    $query->where(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_debut)
                        ->where('heure_fin', '>=', $heure_debut);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_fin)
                        ->where('heure_fin', '>=', $heure_fin);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '>=', $heure_debut)
                        ->where('heure_fin', '<=', $heure_fin);
                    });
                })->exists();

            if ($chauffeurBusy) {
                return response()->json(['error' => 'Le chauffeur n\'est pas disponible à cette heure.'], 422);
            }

            // Vérifiez la disponibilité du bus
            $busBusy = Mission::where('bus_id', $bus_id)
                ->whereDate('date', $date)
                ->where(function($query) use ($heure_debut, $heure_fin) {
                    $query->where(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_debut)
                        ->where('heure_fin', '>=', $heure_debut);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_fin)
                        ->where('heure_fin', '>=', $heure_fin);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '>=', $heure_debut)
                        ->where('heure_fin', '<=', $heure_fin);
                    });
                })->exists();

            if ($busBusy) {
                return response()->json(['error' => 'Le bus n\'est pas disponible à cette heure.'], 422);
            }

            // Vérifiez si le bus a une maintenance prévue
            $busInMaintenance = Maintenance::where('bus_id', $bus_id)
                ->whereDate('maintenance_date', $date)
                ->exists();

            if ($busInMaintenance) {
                return response()->json(['error' => 'Le bus a une maintenance prévue ce jour-là.'], 422);
            }

            // Si toutes les vérifications sont passées, créez la mission
            $mission = Mission::create($request->all());

            return response()->json(['success' => 'Mission créée avec succès', 'mission' => $mission], 201);
        }

        // Mise à jour d'une mission
        public function update(Request $request, Mission $mission)
        {
            $request->validate([
                'date' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i',
                'details_mission' => 'required|string',
                'chauffeur_id' => 'required|exists:chauffeurs,id',
                'bus_id' => 'required|exists:buses,id',
                'vol_id' => 'required|exists:vols,id',
            ]);
        
            $date = $request->input('date');
            $heure_debut = $request->input('heure_debut');
            $heure_fin = $request->input('heure_fin');
            $chauffeur_id = $request->input('chauffeur_id');
            $bus_id = $request->input('bus_id');
        
            // Vérifiez la disponibilité du chauffeur
            $chauffeurBusy = Mission::where('chauffeur_id', $chauffeur_id)
                ->whereDate('date', $date)
                ->where(function($query) use ($heure_debut, $heure_fin) {
                    $query->where(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_debut)
                        ->where('heure_fin', '>=', $heure_debut);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_fin)
                        ->where('heure_fin', '>=', $heure_fin);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '>=', $heure_debut)
                        ->where('heure_fin', '<=', $heure_fin);
                    });
                })->exists();
        
            if ($chauffeurBusy) {
                return response()->json(['error' => 'Le chauffeur n\'est pas disponible à cette heure.'], 422);
            }
        
            // Vérifiez la disponibilité du bus
            $busBusy = Mission::where('bus_id', $bus_id)
                ->whereDate('date', $date)
                ->where(function($query) use ($heure_debut, $heure_fin) {
                    $query->where(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_debut)
                        ->where('heure_fin', '>=', $heure_debut);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '<=', $heure_fin)
                        ->where('heure_fin', '>=', $heure_fin);
                    })->orWhere(function($q) use ($heure_debut, $heure_fin) {
                        $q->where('heure_debut', '>=', $heure_debut)
                        ->where('heure_fin', '<=', $heure_fin);
                    });
                })->exists();
        
            if ($busBusy) {
                return response()->json(['error' => 'Le bus n\'est pas disponible à cette heure.'], 422);
            }
        
            // Si toutes les vérifications sont passées, mettez à jour la mission
            $mission->update($request->all());
        
            return response()->json(['success' => 'Mission mise à jour avec succès', 'mission' => $mission], 200);
        }
        

        // Affichage des détails d'une mission
        public function show(Mission $mission)
        {
            $mission->load('chauffeur', 'bus', 'vol'); // Charger les relations
            return response()->json(['mission' => $mission], 200);
        }
        

        // Démarrer une mission
        public function startMission(Request $request, Mission $mission)
        {
            $chauffeurId = $request->input('chauffeurId');

            if ($mission->statut_mission === 'terminée') {
                return response()->json(['error' => 'Vous ne pouvez pas démarrer une mission déjà terminée.'], 400);
            }
        
            $activeMission = Mission::where('chauffeur_id', $chauffeurId)
                ->where('statut_mission', 'en cours')
                ->first();
        
            if ($activeMission) {
                return response()->json(['error' => 'Vous avez déjà une mission en cours.'], 400);
            }
        
            $mission->update([
                'statut_mission' => 'en cours',
                'heure_commencee' => Carbon::now()
            ]);
        
            return response()->json(['success' => 'Mission démarrée avec succès', 'mission' => $mission], 200);
        }
        
        public function stopMission(Request $request, Mission $mission)
        {
            $mission->update([
                'statut_mission' => 'terminée',
                'heure_terminee' => Carbon::now()
            ]);
        
            return response()->json(['success' => 'Mission terminée avec succès', 'mission' => $mission], 200);
        }
        


            // Liste des missions pour un chauffeur avec filtrage
            public function chauffeurIndex(Request $request)
            {
                $chauffeurId = $request->input('chauffeurId');
                Log::info('Received chauffeur ID: ' . $chauffeurId); 
            
                $filter = $request->input('filter', 'today');
                $missions = $this->getMissionsByFilter($filter, $chauffeurId);
                Log::info('Missions fetched: ' . json_encode($missions)); 
            
                return response()->json(['missions' => $missions, 'filter' => $filter], 200);
            }


        // Filtrage des missions
        private function getMissionsByFilter($filter, $chauffeurId = null)
        {
            $query = Mission::with(['chauffeur', 'bus', 'vol']); 

            if ($chauffeurId) {
                $query->where('chauffeur_id', $chauffeurId);
            }

            switch ($filter) {
                case 'past':
                    $query->whereDate('date', '<', now()->toDateString())
                        ->orderBy('date', 'desc');
                    break;

                case 'future':
                    $query->whereDate('date', '>', now()->toDateString())
                        ->orderBy('date', 'asc');
                    break;

                case 'today':
                default:
                    $query->whereDate('date', now()->toDateString());
                    break;
            }
            

            return $query->get();
        }


    }
