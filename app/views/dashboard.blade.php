@extends('layouts.standard')
@section('content')
<div class="units-row">
    <div class="unit-100 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
        <div class="welcome">
        <h3>Welcome to the Curriculum Mapping tool</h3>
        </div>
        <div class="dashboard">
          There are 16,404 unmapped resources. Please click <a href="/">here to view them</a>
          <p>
          You can also use the menu at the top right.
          </p>

        </div>
    </div>
</div>
@stop
