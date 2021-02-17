<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garden;
use App\Plant;
use App\PlantIcon;

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

                    if ($icons != [""]) {

                        echo("<script>
                                if(console.debug!='undefined'){
                                    console.log('PHP: ".json_encode("test")."');
                                }</script>");

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
                            $plant = Plant::where('plant_name', '=', 'test')
                                ->where('row', '=', $i)
                                ->where('column', '=', $j)
                                ->where('garden_id', '=', $id)
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
            return view('customTiles.create');
        }
    }

    public function store(Request $request, $gardenId, $icon, $row, $column, $position){
        //Create plantIcons record if it does not exist
        $plantIcon = PlantIcon::where('icon', '=', $icon)->first();

        if ($plantIcon === null) {
            $plantIcon = new PlantIcon();
            $plantIcon->icon = $icon;
        
            $plantIcon->save();
        }

        // console.log("test");


        //Create plants record
        $plant = new Plant();
        $plant->plant_name = "test";
        $plant->row = $row;
        $plant->column = $column;
        $plant->icon_location = $position;
        $plant->garden_id = $gardenId;
        $plant->plant_icon_id = $plantIcon->id;

        $plant->save();
    }

}
