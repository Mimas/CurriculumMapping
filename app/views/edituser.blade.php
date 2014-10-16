@extends('layouts.standard')
@section('content')
<style type="text/css">
form {
  display: inline;
}
</style>
<div class="units-row top44">
  <div class="unit-100">
    <h2>{{$user->first_name}} {{$user->last_name}}</h2>
    @if(Session::has('success'))
    <div class="error row-fluid">
    </div>
    @endif
  </div>
  <form method="post" class="forms autoform">
    <div class="units-row">
      <div class="unit-50">
        <!-- main content column -->
        <fieldset>
          <legend>User's Details</legend>
                <label>First name *
                    <input value="{{$user->first_name}}" type="text" name="first_name" class="width-40 <?php if ($errors->has('first_name')) echo 'input-error'; ?>" />
                </label>
                <label>Last name *
                    <input value="{{$user->last_name}}" type="text" name="last_name" class="width-40 <?php if ($errors->has('last_name')) echo 'input-error'; ?>" />
                </label>
                <label>Email *
                    <input value="{{$user->email}}" type="text" name="email" class="width-40 <?php if ($errors->has('email')) echo 'input-error'; ?>" />
                </label>
                <label>Password *
                    <input type="password" name="password" class="width-40 <?php if ($errors->has('password')) echo 'input-error'; ?>" />
                </label>
                <label>Confirm password *
                    <input type="password" name="password2" class="width-40 <?php if ($errors->has('password2')) echo 'input-error'; ?>" />
                </label>
                <p>
                  <a href="/admin/permissions/{{$user->id}}" class="colorbox btn btn-small btn-blue">Permissions&nbsp;<i class="fa fa-edit"></i></a>
                </p>
                 <label>Activated
                    <input type="checkbox" value="1" class="" name="activated" <?php if ($user->activated) echo 'checked="checked"' ?> />
                </label>
         </fieldset>
      </div>
      <div class="unit-50">
        <fieldset>
          <legend>User's Subject Areas</legend>
          <ul class="blocks-2">
          <?php
          if (isset($subjectAreas) ) foreach ($subjectAreas as $area) {
          ?>
          <li><label>{{$area->subject}} <input type="checkbox" name="area[]" class="autosubmit"
                                                id="area_{{$area->id}}" value="{{$area->id}}"
                                                <?php if( in_array($area->id, $userSubjects)) echo 'checked="yes";'  ?>
                                        />
              </label>
          </li>
          <?php
          }
          ?>
          </ul>
        </fieldset>
      </div>
  </div>
  <div class="units-row">
   <div class="unit-100">
     <button class="btn btn-yellow">Save&nbsp;<i class="fa fa-check"></i></button>
   </div>
  </div>
  </form>
</div>
@stop
