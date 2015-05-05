@extends('layouts.standard')
@section('content')
<div class="units-row">
    <div class="unit-66 unit-centered">
        @if(Session::has('errors'))
        <div class="error row-fluid">
            <h4 class="error"><?php echo $errors->first('errors'); ?></h4>
        </div>
        @endif

      <!-- main content column -->
      <fieldset>
      <legend>Login</legend>
          <form method="post" class="forms">
              <label>Email *
                  <input type="text" name="email" class="width-40 <?php if ($errors->has('email')) echo 'input-error'; ?>" />
              </label>
              <label>Password *
                  <input type="password" name="password" class="width-40 <?php if ($errors->has('password')) echo 'input-error'; ?>" />
              </label>
              <button class="btn">Login</button>
           </form>
       </fieldset>
       <a href="<?php echo url('login/reset'); ?>">Forgot password?</a>
    </div>
</div>
@stop
