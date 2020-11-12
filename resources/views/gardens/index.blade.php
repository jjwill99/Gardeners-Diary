@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex-row">
        @foreach($gardens as $garden)
            <!-- <img style="width:100%;height:100%" src="{{ asset('storage/images/'.$garden->picture) }}"> -->
            <div class="card">
                <garden-item :garden_name="'{{$garden->name}}'" picture="{{ asset('storage/images/'.$garden->picture) }}"></garden-item>
            </div>
        @endforeach
    </div>
    <a href="{{action('GardenController@create')}}" class="btn btn-primary">Add a new garden</a>
</div>
@endsection
