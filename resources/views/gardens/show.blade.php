@extends('layouts.app')

@section('content')
<div class="container">
    @for($i = 0; $i < $garden->length; $i++)
        <div class="row">
            @for($j = 0; $j < $garden->width; $j++)
                <garden-grid :garden_width="{{ $garden->width }}"></garden-grid>
            @endfor
        </div>
    @endfor
</div>
@endsection
