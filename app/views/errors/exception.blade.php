@extends('layouts.standard')
@section('content')
        <div class="units-row">
            <div class="unit-100" style="margin-top: 33px;">
              <h3>Sorry, something went wrong</h3>
            </div>
          <div class="unit-100">
            <h4>
            {{$message}}
            </h4>
          </div>
        </div>
@stop
