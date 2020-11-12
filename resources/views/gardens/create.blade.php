@extends('layouts.app')
<!-- define the content section -->
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 ">
      <div class="card">
        <div class="card-header">Create a new garden</div>
        <!-- display the errors -->
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul> @foreach ($errors->all() as $error)
            <li>{{ $error }}</li> @endforeach
          </ul>
        </div><br /> @endif
        <!-- display the success status -->
        @if (\Session::has('success'))
        <div class="alert alert-success">
          <p>{{ \Session::get('success') }}</p>
        </div><br /> @endif
        <!-- define the form -->
        <div class="card-body">
          <form class="form-horizontal" method="POST"
          action="{{url('garden') }}" enctype="multipart/form-data">
          <!-- @csrf -->
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="col-md-8">
            <label >Garden Name</label>
            <input type="text" name="name"
            placeholder="Enter Name" />
          </div>
          <div class="col-md-8">
            <label>Garden Width (m)</label>
            <input type="number" min="0" name="width" />
          </div>
          <div class="col-md-8">
            <label>Garden Length (m)</label>
            <input type="number" min="0" name="length" />
          </div>
          <div class="col-md-8">
            <label>Picture (optional)</label>
            <input type="file" name="picture"
            placeholder="Image file" />
          </div>
          <div class="col-md-6 col-md-offset-4">
            <input type="submit" class="btn btn-primary" />
            <input type="reset" class="btn btn-primary" />
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
@endsection