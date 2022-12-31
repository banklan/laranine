<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocations(){
        $locs = Location::all();

        return response()->json($locs, 200);
    }
}
