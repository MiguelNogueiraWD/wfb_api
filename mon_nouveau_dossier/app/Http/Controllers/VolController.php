<?php

namespace App\Http\Controllers;
use App\Models\Vol;
use Illuminate\Http\Request;

class VolController extends Controller
{
    public function index()
{
    $vols = Vol::all();
    return response()->json($vols);
}

}
