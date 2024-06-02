<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceMapController extends Controller
{
    public function index(Place $place)
    {
        $places = Place::all();
        notify()->success('Laravel Notify is awesome!');
        return view('places.map', compact('places'));
    }


    public function create()
    {
        return view('places.create');
    }

}
