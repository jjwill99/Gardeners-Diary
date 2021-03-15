<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlantActivity;

class PlantActivityController extends Controller
{
    public function getActivities(Request $request){
        $plantId = $request->input('id');

        if ($plantId == -1) {
            $activities = PlantActivity::where('completed', '=', '0')->orderBy('time', 'DESC')->get();
        } else {
            $activities = PlantActivity::where('plant_id', '=', $plantId)->orderBy('time')->get();
        }

        return response()->json($activities);
    }

    public function store(Request $request){
        $activity = $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'time' => 'required',
            'plantId' => 'required',
            'frequency' => 'sometimes|nullable'
        ]);

        $activity = new PlantActivity;
        $activity->name = $request->input('name');
        $activity->description = $request->input('description');
        $activity->time = $request->input('time');
        $activity->completed = 0;
        $activity->plant_id = $request->input('plantId');
        $activity->frequency = $request->input('frequency');

        $activity->save();

        //Generate a redirect HTTP response with a success message
        return back()->with('success', 'Activity has been added');
    }

    public function complete(Request $request){
        $id = $request->input('id');

        $activity = PlantActivity::find($id);
        $activity->completed = 1;

        if ($activity->frequency != "null") {
            $repeat = new PlantActivity;
            $repeat->name = $activity->name;
            $repeat->description = $activity->description;
            $repeat->completed = 0;
            $repeat->plant_id = $activity->plant_id;
            $repeat->frequency = $activity->frequency;
            
            $today = strtotime(date("Y-m-d H:i:s"));
            $date = strtotime($activity->time);

            do {
                $date = strtotime("+" . $repeat->frequency, $date);
            } while ($date < $today);

            $repeat->time = date("Y-m-d H:i:s", $date);

            $repeat->save();
        }

        $activity->save();
    }

    public function update(Request $request){
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

    public function destroy(Request $request)
    {
        $id = $request->input('id');

    	$activity = PlantActivity::find($id);

        if ($request->input('allOccurrences')) {
            $activity->delete();
            return redirect('garden')->with('success','Activities have been deleted');
        } else {
            $nextActivity = new PlantActivity; //create method for reuse
            $nextActivity->name = $activity->name;
            $nextActivity->description = $activity->description;
            $nextActivity->frequency = $activity->frequency;
            $nextActivity->plant_id = $activity->plant_id;
            $nextActivity->completed = 0;

            $today = strtotime(date("Y-m-d H:i:s"));
            $date = strtotime($activity->time);

            do {
                $date = strtotime("+" . $nextActivity->frequency, $date);
            } while ($date < $today);

            $nextActivity->time = date("Y-m-d H:i:s", $date);

            $nextActivity->save();
            $activity->delete();

            return redirect('garden')->with('success','Activity has been deleted');
        }
    }
}
