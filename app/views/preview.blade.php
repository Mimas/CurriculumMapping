@extends('layouts.plain')
@section('content')
<style type="text/css">
form {
  display: inline;
}
html, body { height: 100% }
.phpdebugbar-restore-btn {
  display: none!important;
}
</style>
<div class="units-row">
    <div class="unit-100 unit-centered">
    {{$bitstream->render()}}
    </div>
</div>
@stop
