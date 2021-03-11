@extends('layouts.app')

<style>
    .grid {
        margin-left: 15vw;
        margin-right: 5vw;
    }

    .sidebar {
        width: 12vw;
        position: fixed;
        z-index: 1;
        top: 75px;
        left: 10px;
        background: #eee;
        overflow-x: hidden;
    }

    .save {
        position: fixed;
        z-index: 1;
        top: 300px;
        left: 40px;
        overflow-x: hidden;
    }
</style>

@section('content')
<div>
    <form method="POST" action="{{ action('GridController@update', $garden->id) }}">
        {!! method_field('patch') !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <div class="sidebar border border-dark">
            <center>SELECT A TILE:</center>
            <garden-tile colour="green" tile_name="Grass" background_colour="gold"></garden-tile>
            <garden-tile colour="saddleBrown" tile_name="Soil"></garden-tile>
            <garden-tile colour="burlyWood" tile_name="Tile"></garden-tile>
            <garden-tile colour="lightSkyBlue" tile_name="Water"></garden-tile>

            <?php

                foreach ($plants as $plant) {
                    echo('<garden-tile icon="' . $plant->icon . '" tile_name="' . $plant->plant_name . '" del="' . action('PlantController@destroy', $plant->id) .
                        '" csrf="' . csrf_token() . '"></garden-tile>');
                }

            ?>
            <center>
                <input type="submit" class="btn btn-primary rounded-0" style="width: 49%;" name="action" value="Add Plant" />
                <input type="submit" class="btn btn-primary rounded-0" style="width: 49%;" name="action" value="Save Layout" onclick="noConfirm()" />
            </center>
        </div>
    
        <div class="grid">
            <div class="row justify-content-center">
                <?php
                    $tiles = preg_split("/(},{)/", json_decode($garden->grid)[0]);

                    $row = 0;

                    foreach ($tiles as $value) {
                        if ($value[0] != '{') {
                            $value = '{'.$value;
                        }
                        if (substr($value, -1) != '}') {
                            $value .= '}';
                        }

                        $tile = json_decode($value, true);

                        $newRow = $tile["row"];

                        if ($newRow != $row) {
                            ?>
                            </div>
                            <div class="row justify-content-center">
                            <?php
                            $row = $newRow;
                        }


                        $pictures = array('', '', '', '');

                        $temp = $plantIcons->where('row', '=', $tile['row'])
                                    ->where('column', '=', $tile['column']);

                        $filepath = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/";

                        foreach ($temp as $icon) {
                            if ($icon->one == 1) {
                                $pictures[0] = $filepath . $icon->icon;
                            }
                            if ($icon->two == 1) {
                                $pictures[1] = $filepath . $icon->icon;
                            }
                            if ($icon->three == 1) {
                                $pictures[2] = $filepath . $icon->icon;
                            }
                            if ($icon->four == 1) {
                                $pictures[3] = $filepath . $icon->icon;
                            }
                        }

                        $pictures = '["' . implode('","', $pictures) . '"]';

                        ?>
                        <garden-grid page_type="edit" :garden_width="{{ $garden->width }}" :garden_length="{{ $garden->length }}" colour="{{ $tile['colour'] }}"
                            :grid_row="{{ $tile['row'] }}" :grid_column="{{ $tile['column'] }}" v-bind:pictures="{{ $pictures }}"></garden-grid>
                        <?php
                    }
                ?>
            </div>
        </div>
    </form>

</div>

@endsection


<script>

window.onbeforeunload = function(){
        return "Adding a new plant option will undo any unsaved changes.";
    };

function noConfirm(){
    window.onbeforeunload = null;
};

</script>