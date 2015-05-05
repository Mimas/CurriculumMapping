@extends('layouts.standard')
@section('content')
<div class="units-row">
    <div class="unit-66 unit-centered">
        @if(Session::has('errors'))
        <div class="error row-fluid">
            <h4 class="error"><?php echo $errors->first('errors'); ?></h4>
        </div>
        @endif

        @if(Session::has('success'))
        <div class="error row-fluid">
            <h4>Your password has been changed</h4>
            <p>Click <a href="/login">here</a> to login</p>
        </div>
        @endif

      <!-- main content column -->
      <fieldset>
      <legend>Login</legend>
          <form method="post" class="forms">
              <label>Email *
                  <input type="text" name="email" class="width-40 <?php if ($errors->has('email')) echo 'input-error'; ?>" />
              </label>
              <label>Code *
                  <input type="text" name="code" class="width-40 <?php if ($errors->has('code')) echo 'input-error'; ?>" />
              </label>
              <label>Password *
                  <input type="password" name="password" class="width-40 <?php if ($errors->has('password')) echo 'input-error'; ?>" />
              </label>
              <label>Repeat password *
                  <input type="password" name="password2" class="width-40 <?php if ($errors->has('password')) echo 'input-error'; ?>" />
              </label>

              <button class="btn">Reset</button>
           </form>
       </fieldset>
    </div>
</div>
@stop
