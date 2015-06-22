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
            <h4>A code along with instructions has been sent to your email address</h4>
        </div>
        @endif

      <!-- main content column -->
      <fieldset>
      <legend>Login</legend>
          <form method="post" class="forms">
              <label>Email *
                  <input type="text" name="email" class="width-40 <?php if ($errors->has('email')) echo 'input-error'; ?>" />
              </label>
              <button class="btn">Reset</button>
           </form>
       </fieldset>
    </div>
</div>
@stop
