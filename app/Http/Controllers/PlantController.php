<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plant;
use App\PlantLocation;

class PlantController extends Controller
{
    /**
     * Create a new plant record.
     */
    public function store(Request $request){
        //Form validation
        $plant = $this->validate(request(), [
            'name' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);

        //Create a Plant object and set its values from the input
        $plant = new Plant;
        $plant->plant_name = $request->input('name');
        $plant->garden_id = $request->input('gardenId');

        //Handles the uploading of the picture
        if ($request->hasFile('icon')){
            //Gets the filename with the extension
                $fileNameWithExt = $request->file('icon')->getClientOriginalName();
            //Just gets the filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Just gets the extension
                $extension = $request->file('icon')->getClientOriginalExtension();
            //Gets the filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Uploads the picture
                $path = $request->file('icon')->storeAs('public/images', $fileNameToStore);
                $plant->icon = $fileNameToStore;
        }

        //Save the Plant object
        $plant->save();
        
        //Generate a redirect HTTP response with a success message
        return redirect('garden')->with('success', 'Plant tile has been added');
    }

    /**
     * Retrieve all plant information for the specified garden.
     */
    public function getPlants(Request $request){
        $gardenId = $request->input('gardenId');

        $plants = Plant::select('id', 'plant_name', 'icon')->where('garden_id', '=', $gardenId)->orderBy('id')->get();

        return response()->json($plants);
    }
    
    /**
     * Retrieve all plant location information for the specified garden.
     */
    public function getLocations(Request $request){
        $gardenId = $request->input('gardenId');

        $locations = PlantLocation::join('plants', 'plant_locations.plant_id', '=', 'plants.id')
            ->join('gardens', 'plants.garden_id', '=', 'gardens.id')
            ->select('plant_locations.id', 'plant_locations.row', 'plant_locations.column', 'plant_locations.icon_location', 'plants.icon', 'plant_locations.plant_id', 'plants.plant_name')
            ->where('gardens.id', '=', $gardenId)
            ->orderBy('plant_locations.id')
            ->get();

        return response()->json($locations);
    }

    /**
     * Delete the specified plant record from the database.
     */
    public function destroy(Request $request)
    {
        $plantId = $request->input('id');

    	$plant = Plant::find($plantId);
        $plant->delete();

        $locations = PlantLocation::where('plant_id', '=', $plantId)->get();

        foreach ($locations as $location) {
            $location->delete();
        }
                
        return back()->with('success', 'Plant has been deleted');
    }
}
