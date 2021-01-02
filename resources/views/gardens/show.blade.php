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
    <div class="sidebar border border-dark">
        <center>SELECT A TILE:</center>
        <garden-tile colour="green" tile_name="Grass" background_colour="gold"></garden-tile>
        <garden-tile colour="saddleBrown" tile_name="Soil"></garden-tile>
        <garden-tile colour="burlyWood" tile_name="Tile"></garden-tile>
    </div>
    
    <form method="POST" action="{{ action('GardenController@save') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="grid">
            @for($i = 0; $i < $garden->length; $i++)
                <div class="row justify-content-center">
                    @for($j = 0; $j < $garden->width; $j++)
                        <garden-grid :garden_width="{{ $garden->width }}" :garden_length="{{ $garden->length }}"
                            :grid_row="{{ $i }}" :grid_column="{{ $j }}"></garden-grid>
                    @endfor
                </div>
            @endfor
        </div>
        <div class="save">
            <input type="submit" class="btn btn-primary" value="Save Layout" />
        </div>
    </form>

</div>
@endsection
