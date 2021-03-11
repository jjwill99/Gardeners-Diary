<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garden;
use App\Plant;
use App\PlantLocation;

class GridController extends Controller
{
    public function update(Request $request, $id){
        if ($request->input('action') == 'Save Layout') {
            //Store state of grid
            $garden = Garden::find($id);
            $row = $garden->length - 1;
            $column = $garden->width - 1;

            //Create JSON
            $json = '["';

            //Loop through form inputs
            for ($i=0; $i <= $row; $i++) { 
                for ($j=0; $j <= $column; $j++) { 
                    $json .= '{\\"row\\":' . $i . ',\\"column\\":' . $j . ',\\"colour\\":\\"' . $request->input($i.",".$j) . '\\"},';
                }
            }
            $json = rtrim($json, ",");
            $json .= '"]';
            
            $garden->grid = $json;
            $garden->save();
            
            //Store state of plants

            //Loop through form inputs
            for ($i=0; $i <= $row; $i++) { 
                for ($j=0; $j <= $column; $j++) {
                    
                    $icons =  explode("|", $request->input('icons,'.$i.",".$j));
                    $numIcons = array();

                    if ($icons != ["", "", "", ""]) {

                        // echo("<script>
                        // if(console.debug!='undefined'){
                        //     console.log('PHP: ".json_encode($icons)."');
                        // }</script>");

                        for ($n = 1; $n <= count($icons); $n++) {
                            $icon = $icons[$n-1];
                            if($icon != ""){
                                if (array_key_exists($icon, $numIcons)) {
                                // if ($numIcons[$icon] != "") {
                                    // Add icon position to string
                                    $numIcons[$icon] .= $n;
                                } else {
                                    //Add new item to array
                                    $new_array = array($icon => $n);
                                    $numIcons = array_merge($numIcons, $new_array);
                                }
                            }
                        }

                        foreach ($numIcons as $icon => $position) {
                            $icon = substr($icon, strrpos($icon, '/') + 1);

                            $plant = Plant::join('plant_locations', 'plants.id', '=', 'plant_locations.plant_id')
                                ->where('plants.plant_name', '=', 'test')
                                ->where('plant_locations.row', '=', $i)
                                ->where('plant_locations.column', '=', $j)
                                ->where('plants.garden_id', '=', $id)
                                ->first();

                            if ($plant === null) {
                                $this->store($request, $id, $icon, $i, $j, $position);
                            }

                        }



                        // $plantInfo = '{\\"row\\":' . $i . ',\\"column\\":' . $j . ',\\"colour\\":\\"' . $request->input($i.",".$j) . '\\"},';
                        // $plant = Plant::where('plant_name', '=', '')
                        //     ->where('row', '=', $i)
                        //     ->where('column', '=', $j)
                        //     ->where('garden_id', '=', $id)
                        //     ->get();
                        // if ($plant == null) {
                        //     $this->store($request, $id, $request->input(''), $i, $j);
                        // }
                    }
                }
            }

            return back()->with('success', ' Garden has been updated');
        } else {
            return view('plants.create');
        }
    }

    public function store(Request $request, $gardenId, $icon, $row, $column, $position){
        //Create plantIcons record if it does not exist
        $plant = Plant::where('icon', '=', $icon)->first();

        echo $icon;

        // if ($plant === null) {
        //     //Create plants record
        //     $plant = new Plant();
        //     $plant->plant_name = "test";
        //     $plant->icon = $icon;
        //     $plant->garden_id = $gardenId;

        //     $plant->save();
        // }

        $plantLocation = new PlantLocation();
        $plantLocation->row = $row;
        $plantLocation->column = $column;
        $plantLocation->icon_location = $position;
        $plantLocation->plant_id = $plant->id;
    
        $plantLocation->save();
    }

}
