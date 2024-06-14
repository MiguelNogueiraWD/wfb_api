<?php

namespace App\Http\Controllers;
use App\Models\Bus;
use Illuminate\Http\Request;
use App\Models\Maintenance;

class BusController extends Controller
{
    //récupération de la liste des bus
    public function index()
    {
    $buses = Bus::all();
    return response()->json($buses);
    }


    //création d'une maintenance pour un bus
    public function createMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_date' => 'required|date',
            'maintenance_description' => 'required|string',
            'cout' => 'required|numeric',
            'bus_id' => 'required|exists:buses,id',
        ]);

        $maintenance = new Maintenance([
            'maintenance_date' => $request->maintenance_date,
            'maintenance_description' => $request->maintenance_description,
            'cout' => $request->cout,
            'bus_id' => $request->bus_id,
        ]);

        $maintenance->save();

        return response()->json(['success' => 'Maintenance créée avec succès', 'maintenance' => $maintenance], 201);
    }

    //récupération des détails d'un bus
    public function getBusDetails($id, Request $request)
    {
        $filter = $request->input('filter', 'today');
        $bus = Bus::with(['maintenances', 'missions.chauffeur', 'missions' => function($query) use ($filter) {
            switch ($filter) {
                case 'past':
                    $query->whereDate('date', '<', now()->toDateString())->orderBy('date', 'desc');
                    break;
                case 'future':
                    $query->whereDate('date', '>', now()->toDateString())->orderBy('date', 'asc');
                    break;
                case 'today':
                default:
                    $query->whereDate('date', now()->toDateString());
                    break;
            }
        }])->findOrFail($id);
        
        return response()->json(['bus' => $bus], 200);
    }
    
    //création de bus
    public function createBus(Request $request)
    {
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'immatriculation' => 'required|unique:buses',
            'capacite' => 'required|integer',
            'statut' => 'required'
        ]);

        $bus = Bus::create($request->all());

        return response()->json(['status' => 'success', 'message' => 'Bus créé avec succès.', 'bus' => $bus], 201);
    }

    
}
