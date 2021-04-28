<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlantActivity;

class PlantActivityController extends Controller
{
    /**
     * Retrieve all activities for the specified plant or garden.
     */
    public function getActivities(Request $request){
        $plantId = $request->input('id');
        $gardenId = $request->input('gardenId');

        if ($plantId == -1) {
            $activities = PlantActivity::join('plants', 'plant_activities.plant_id', '=', 'plants.id')
                            ->select('plant_activities.*')
                            ->where('plant_activities.completed', '=', '0')
                            ->where('plants.garden_id', '=', $gardenId)
                            ->orderBy('time', 'DESC')
                            ->get();
        } else {
            $activities = PlantActivity::where('plant_id', '=', $plantId)->orderBy('time')->get();
        }

        return response()->json($activities);
    }

    /**
     * Create a new activity record.
     */
    public function store(Request $request){
        //Form validation
        $activity = $this->validate(request(), [
            'name' => 'required',
            'description' => 'sometimes',
            'time' => 'required',
            'completed' => 'required',
            'plantId' => 'required',
            'frequency' => 'sometimes|nullable'
        ]);

        //Create a PlantActivity object and set its values from the input
        $activity = new PlantActivity;
        $activity->name = $request->input('name');
        $activity->description = $request->input('description') == null ? '' : $request->input('description');
        $activity->time = $request->input('time');
        $activity->completed = $request->input('completed') == 'true' ? 1 : 0;
        $activity->plant_id = $request->input('plantId');
        $activity->frequency = $request->input('frequency');

        $activity->save();

        //Generate a redirect HTTP response with a success message
        return back()->with('success', 'Activity has been added');
    }

    /**
     * Update the database to complete the specified activity.
     */
    public function complete(Request $request){
        $id = $request->input('id');

        $activity = PlantActivity::find($id);
        $activity->completed = 1;

        //Create the next scheduled activity if it should be repeated.
        if ($activity->frequency != "null") {
            $this->repeatActivity($activity);
        }

        $activity->save();
    }

    /**
     * Update the database to reflect the user's changes to the activity details.
     */
    public function update(Request $request){
        //Form validation
        $activity = $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'time' => 'required',
            'frequency' => 'sometimes|nullable',
            'activityId' => 'required'
        ]);

        $id = $request->input('activityId');
        $activity = PlantActivity::find($id);

        $activity->name = $request->input('name');
        $activity->description = $request->input('description');
        $activity->time = $request->input('time');
        $activity->frequency = $request->input('frequency');

        $activity->save();

        //Generate a redirect HTTP response with a success message
        return back()->with('success', 'Activity has been updated');
    }

    /**
     * Create the next repeated activity.
     * $activity is assumed to be repeating.
     */
    public function repeatActivity($activity)
    {
        $nextActivity = new PlantActivity;
        $nextActivity->name = $activity->name;
        $nextActivity->description = $activity->description;
        $nextActivity->frequency = $activity->frequency;
        $nextActivity->plant_id = $activity->plant_id;
        $nextActivity->completed = 0;

        //Convert date to correct format
        $today = strtotime(date("Y-m-d H:i:s"));
        $date = strtotime($activity->time);

        //Keep adding activity frequency until activity is due in the future
        do {
            $date = strtotime("+" . $nextActivity->frequency, $date);
        } while ($date < $today);

        $nextActivity->time = date("Y-m-d H:i:s", $date);

        $nextActivity->save();
    }

    /**
     * Delete the specified activity record from the database.
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

    	$activity = PlantActivity::find($id);

        if ($request->input('allOccurrences')) {
            //Delete all occurrences of a repeating activity
            $activity->delete();
            return redirect('garden')->with('success','Activities have been deleted');
        } else {
            if ($activity->frequency != 'null') {
                //Create the next repeated activity
                $this->repeatActivity($activity);
            }
            $activity->delete();

            return redirect('garden')->with('success','Activity has been deleted');
        }
    }
}
