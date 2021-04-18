<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garden;
use App\Plant;
use App\PlantLocation;

class GardenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $userId = \Auth::user()->id;
        $gardens = Garden::where('user_id', '=', $userId)
                    ->orderBy('id')
                    ->get();

        return response()->json($gardens);
    }

    public function checkUser(Request $request){
        $gardenId = $request->input('gardenId');
        
        $userId = \Auth::user()->id;
        $gardenUserId = Garden::find($gardenId)->user_id;
        $validated = $userId == $gardenUserId;

        return response()->json([
            'value' => $validated
        ]);
    }

    public function show(Request $request){
        $id = $request->fullUrl();
        $id = str_replace('=', '', substr($id, strpos($id, '?') + 1));

        $garden = Garden::find($id);
        $plants = Plant::orderBy('id')->get()->where('garden_id', '=', $id);

        $plantIcons = Plant::join('plant_locations', 'plants.id', '=', 'plant_locations.plant_id')
            ->select('plants.icon', 'plant_locations.row', 'plant_locations.column', 'plant_locations.icon_location')
            ->selectRaw('(plant_locations.icon_location LIKE "%1%") AS one')
            ->selectRaw('(plant_locations.icon_location LIKE "%2%") AS two')
            ->selectRaw('(plant_locations.icon_location LIKE "%3%") AS three')
            ->selectRaw('(plant_locations.icon_location LIKE "%4%") AS four')
            ->where('plants.garden_id', '=', $garden->id)
            ->get();

        return view('gardens.show', array('garden'=> $garden, 'plants'=>$plants, 'plantIcons'=>$plantIcons));
    }

    public function create(){
        return view('gardens.create');
    }

    public function update(Request $request){
        $gardenId = $request->input('gardenId');

        $garden = Garden::find($gardenId);        
        $garden->grid = $request->input('grid');
        $garden->save();

        //Remove existing plant locations
        $locations = PlantLocation::join('plants', 'plant_locations.plant_id', '=', 'plants.id')
                        ->join('gardens', 'plants.garden_id', '=', 'gardens.id')
                        ->select('plant_locations.*')
                        ->where('gardens.id', '=', $gardenId)
                        ->get();

        foreach ($locations as $loc) {
            $loc->delete();
        }

        //Save plant locations
        $locations = json_decode($request->input('locations'));

        foreach ($locations as $location) {
            //Create new record
            $record = new PlantLocation;

            $record->row = $location->row;
            $record->column = $location->column;
            $record->icon_location = $location->icon_location;

            $plant = Plant::select('id')
                            ->where('icon', '=', $location->icon)
                            ->first();

            $record->plant_id = $plant->id;

            $record->save();
        }

        return back()->with('success', ' Garden has been updated');
    }

    public function store(Request $request){
        //form validation
        if ($request->input('picture') == 'null') {
            $garden = $this->validate(request(), [
                'name' => 'required',
                'width' => 'required',
                'length' => 'required'
            ]);
        } else {
            $garden = $this->validate(request(), [
                'name' => 'required',
                'width' => 'required',
                'length' => 'required',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ]);
        }

        //Create a Garden object and set its values from the input
        $garden = new Garden;
        $garden->user_id = \Auth::user()->id;
        $garden->name = $request->input('name');
        $garden->width = $request->input('width');
        $garden->length = $request->input('length');

        //Create JSON
        $json = array();
        for ($i=0; $i < $garden->length; $i++) { 
            for ($j=0; $j < $garden->width; $j++) { 
                array_push($json, array("row" => $i, "column" => $j, "colour" => "green"));
            }
        }

        $garden->grid = json_encode($json);

        //Handles the uploading of the picture
        if ($request->input('picture') != 'null' && $request->hasFile('picture')){
            //Gets the filename with the extension
                $fileNameWithExt = $request->file('picture')->getClientOriginalName();
            //just gets the filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Just gets the extension
                $extension = $request->file('picture')->getClientOriginalExtension();
            //Gets the filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Uploads the picture
                $path = $request->file('picture')->storeAs('public/images', $fileNameToStore);
                $garden->picture = $fileNameToStore;
        }

        //Save the Garden object
        $garden->save();
        
        //Generate a redirect HTTP response with a success message
        return back()->with('success', 'Garden has been added');
    }

    public function edit(){
        $garden = Garden::find($id);
        $plants = Plant::orderBy('id')->get()->where('garden_id', '=', $id);

        $plantIcons = Plant::join('plant_locations', 'plants.id', '=', 'plant_locations.plant_id')
            ->select('plants.icon', 'plant_locations.row', 'plant_locations.column', 'plant_locations.icon_location')
            ->selectRaw('(plant_locations.icon_location LIKE "%1%") AS one')
            ->selectRaw('(plant_locations.icon_location LIKE "%2%") AS two')
            ->selectRaw('(plant_locations.icon_location LIKE "%3%") AS three')
            ->selectRaw('(plant_locations.icon_location LIKE "%4%") AS four')
            ->where('plants.garden_id', '=', $garden->id)
            ->get();

        return view('gardens.edit', array('garden'=> $garden, 'plants'=>$plants, 'plantIcons'=>$plantIcons));
    }

    public function getGarden(Request $request){
        $gardenId = $request->input('gardenId');
        
        $garden = Garden::where('id', '=', $gardenId)->first();

        return response()->json($garden);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

    	$garden = Garden::find($id);
    	$garden->delete();

        $plants = Plant::where("garden_id", $id)->get();
        foreach ($plants as $plant) {
            $locations = PlantLocation::where('plant_id', $plant->id)->get();
            foreach ($locations as $location) {
                $location->delete();
            }

            $plant->delete();
        }
        
        return redirect('garden')->with('success','Garden has been deleted');
    }

}
