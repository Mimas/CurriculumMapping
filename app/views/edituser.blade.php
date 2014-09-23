@extends('layouts.standard')
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
      <legend>Edit User</legend>
          <form method="post" class="forms">
              <label>First name *
                  <input value="{{$user->first_name}}" type="text" name="first_name" class="width-40 <?php if ($errors->has('first_name')) echo 'input-error'; ?>" />
              </label>
              <label>Last name *
                  <input  value="{{$user->last_name}}" type="text" name="last_name" class="width-40 <?php if ($errors->has('last_name')) echo 'input-error'; ?>" />
              </label>
              <label>Email *
                  <input  value="{{$user->email}}" type="text" name="email" class="width-40 <?php if ($errors->has('email')) echo 'input-error'; ?>" />
              </label>
              <label>Password *
                  <input type="password" name="password" class="width-40 <?php if ($errors->has('password')) echo 'input-error'; ?>" />
              </label>
              <label>Confirm password *
                  <input type="password" name="password2" class="width-40 <?php if ($errors->has('password2')) echo 'input-error'; ?>" />
              </label>
              <p>
                <a href="/admin/permissions/{{$user->id}}" rel="shadowbox">Manage  permissions</a>
              </p>
               <label>Activated
                  <input type="checkbox" value="1" class="" name="activated" <?php if ($user->activated) echo 'checked="checked"' ?> />
              </label>
              <button class="btn">Save</button>
           </form>
       </fieldset>

    </div>
</div>
@stop
