@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($gardens as $garden)
            <garden-item garden_name="{{$garden->name}}" picture="{{ asset('storage/images/'.str_replace(' ', '%20', $garden->picture)) }}"
                garden_link="{{action('GardenController@show', $garden->id)}}" csrf="{{ csrf_token() }}" del="{{ action('GardenController@destroy', $garden->id) }}"></garden-item>
        @endforeach
    </div>
    <a href="{{action('GardenController@create')}}" class="btn btn-primary">Add a new garden</a>
</div>
@endsection
