<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garden;
use App\Plant;
use App\PlantLocation;
use App\GardenHistory;
use App\PlantHistory;
use App\PlantLocationHistory;

class GardenHistoryController extends Controller
{
    /**
     * Check that the user owns the selected garden history.
     */
    public function checkUser(Request $request){
        $historyId = $request->input('historyId');
        
        $userId = \Auth::user()->id;
        $historyUserId = GardenHistory::join('gardens', 'garden_histories.garden_id', '=', 'gardens.id')
                            ->select('gardens.user_id')
                            ->where('garden_histories.id', $historyId)
                            ->first();

        $validated = $userId == $historyUserId->user_id;

        return response()->json([
            'value' => $validated
        ]);
    }

    /**
     * Create a new garden history record.
     */
    public function store(Request $request){
        //Form validation
        $history = $this->validate(request(), [
            'gardenId' => 'required',
            'historyName' => 'required',
            'date' => 'required'
        ]);

        $gardenId = $request->input('gardenId');

        $gardenInformation = Garden::find($gardenId);

        $plantInformation = Plant::where('garden_id', $gardenId)
                                ->get();

        //Create a GardenHistory object and set its values from the input
        $history = new GardenHistory;
        $history->name = $request->input('historyName');
        $history->width = $gardenInformation->width;
        $history->length = $gardenInformation->length;
        $history->grid = $gardenInformation->grid;
        $history->date = $request->input('date');
        $history->garden_id = $gardenId;
        $history->save();

        //Loop through the garden's plants and create a PlantHistory record for each one
        foreach ($plantInformation as $plant) {
            $plantHistory = new PlantHistory();
            $plantHistory->plant_name = $plant->plant_name;
            $plantHistory->icon = $plant->icon;
            $plantHistory->history_id = $history->id;
            $plantHistory->save();

            $locationInformation = PlantLocation::where('plant_id', $plant->id)->get();

            //Loop through the garden's plant location and create a PlantLocationHistory record for each one
            foreach ($locationInformation->where('plant_id', $plant->id) as $location) {                
                $locationHistory = new PlantLocationHistory();
                $locationHistory->row = $location->row;
                $locationHistory->column = $location->column;
                $locationHistory->icon_location = $location->icon_location;
                $locationHistory->plant_history_id = $plantHistory->id;
                $locationHistory->save();
            }
        }

    }

    /**
     * Retrieve all garden histories information for the specified garden.
     */
    public function getHistories(Request $request){
        $gardenId = $request->input('gardenId');

        $histories = GardenHistory::where('garden_id', '=', $gardenId)->orderBy('date')->get();

        return response()->json($histories);
    }

    /**
     * Retrieve all garden histrory information for the specified garden history.
     */
    public function getGarden(Request $request){
        $historyId = $request->input('historyId');

        $history = GardenHistory::where('id', $historyId)->first();

        return response()->json($history);
    }

    /**
     * Retrieve all plant history information for the specified garden history.
     */
    public function getPlants(Request $request){
        $historyId = $request->input('historyId');

        $plants = PlantHistory::select('id', 'plant_name', 'icon')->where('history_id', $historyId)->orderBy('id')->get();

        return response()->json($plants);
    }

    /**
     * Retrieve all plant location history information for the specified garden history.
     */
    public function getLocations(Request $request){
        $historyId = $request->input('historyId');

        $locations = PlantLocationHistory::join('plant_histories', 'plant_location_histories.plant_history_id', '=', 'plant_histories.id')
            ->join('garden_histories', 'plant_histories.history_id', '=', 'garden_histories.id')
            ->select('plant_location_histories.id', 'plant_location_histories.row', 'plant_location_histories.column', 'plant_location_histories.icon_location', 'plant_histories.icon', 'plant_location_histories.plant_history_id', 'plant_histories.plant_name')
            ->where('garden_histories.id', $historyId)
            ->orderBy('plant_location_histories.id')
            ->get();

        return response()->json($locations);
    }

    /**
     * Delete the specified garden history record (and all plant/plant location history) from the database.
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

    	$history = GardenHistory::find($id);
    	$history->delete();

        $plants = PlantHistory::where("history_id", $id)->get();
        foreach ($plants as $plant) {
            $locations = PlantLocationHistory::where('plant_history_id', $plant->id)->get();
            foreach ($locations as $location) {
                $location->delete();
            }

            $plant->delete();
        }

        return redirect('garden')->with('success','History has been deleted');
    }
}
