@extends('layouts.standard', array('error'=>1))
@section('content')
  <div class="units-row">
    <div class="unit-100" style="margin-top: 44px;">
      <h3>Sorry, something went wrong</h3>
    </div>
    <div class="unit-100">
      <h4>{{$message}}</h4>
    </div>
</div>
@stop
