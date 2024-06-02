<?php

namespace App\Http\Controllers;

use App\Http\Resources\Place as PlaceResource;
// use App\Http\Resources\Place;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class PlaceController extends Controller
{

    public function index()
    {   
        return view('places.index');
    }


    public function create()
    {
        return view('places.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'address'   => 'required|min:5',
            'description' => 'required|min:5',
            'longitude'  => 'required',
            'latitude'  => 'required'
        ]);

        if ($request->hasFile('image')) {
            $fileName = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('places', $fileName, 'public');
            $placeImage = 'storage/' . $path;
        }

        Place::create([
            'name' => $request->name,
            'address'  => $request->address,
            'description' => $request->description,
            'image' => $placeImage,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);
        notify()->success('Place has been created');
        return redirect()->route('places.index');
    }


    public function show(Place $place)
    {
        return view('places.detail', [
            'place' => $place,
        ]);
    }


    public function edit(Place $place)
    {
        return view('places.edit', [
            'place' => $place,
        ]);
    }


    public function update(Request $request, Place $place)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'address'   => 'required|min:5',
            'description' => 'required|min:5',
            'longitude'  => 'required',
            'latitude'  => 'required'
        ]);

        if ($request->hasFile('image')) {

            if (File::exists($place->gambar)) {
                File::delete($place->gambar);
            }

            $fileName = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('places', $fileName, 'public');
            $place->image = 'storage/' . $path;
        }  

        $place->update([
            'name' => $request->name,
            'address'  => $request->address,
            'description' => $request->description,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        notify()->info('Place has been updated');
        return redirect()->route('places.index');
    }

    public function destroy(Place $place)
    {
        $place->delete();
        notify()->warning('Place has been deleted');
        return redirect()->route('places.index');
    }
}
