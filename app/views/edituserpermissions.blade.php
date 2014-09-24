@extends('layouts.plain')
@section('content')
<style type="text/css">
form {
  display: inline;
}
</style>
<div class="units-row">
    <div class="unit-66 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
       <h1>{{$user->first_name}} {{$user->last_name}}</h1>
      <!-- main content column -->
      <fieldset>
      <legend>Edit Permissions</legend>
          <form method="post" class="forms">
              <?php 
              foreach($classes as $class) { ?>
              <label>
              {{ucfirst($class->class)}}
                <ul class="forms-inline-list">
                  <?php foreach ($permissions as $permission) 
                    if($permission->class == $class->class) { 
                  ?>
                    <li><input type="checkbox" name="{{$class->class}}_{{$permission->action}}" 
                      <?php if ( \Bentleysoft\Helper::userHasAccess(array($class->class.'.'.$permission->action), $user->id  )) 
                        echo 'checked="checked"'; ?>  
                      /> &nbsp;<label>{{ucfirst($permission->action)}}</label>
                    </li>

                  <?php } ?>
                </ul>
              </label>
              <?php 
              } 
              ?>
              <button class="btn btn-yellow">Save&nbsp;<i class="fa fa-check"></i></button>
           </form>
       </fieldset>

    </div>
</div>
@stop
