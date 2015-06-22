@extends('layouts.standard')
@section('content')
<style type="text/css">
form {
  display: inline;
}
.breathe {
  margin-top: 18px;
}
.control-group {
  margin-top: 18px;  
}
</style>
  <div class="units-row">
    <div class="unit-90 unit-centered">

    {{ Form::open(array('url' => 'contact')) }}
    @if(Session::has('errors'))
      <div class="error row-fluid">
        <h5 class="invalid">Please fill in the form below correctly</h5>                
      </div>
    @endif
    @if(Session::has('success'))
      <div class="alert-box success large-8 columns left">
        <h5>{{ Session::get('success') }}</h5>
      </div>
    @endif
  </div>
</div>
<div class="units-row" >
  <div class="unit-90 unit-centered">  
    <form class="forms">
      <fieldset>
      <legend>Contact us</legend>

      <!-- Text input-->
      <div class="control-f <?php if ($errors->has('name')) echo 'inputError'; ?>">
        <label  for="Name">Name *</label>
        <div class="controls">
          <input class="width-100" id="name" name="name" 
              value="{{$user->first_name or Input::old('name')}}" 
              type="text" 
              placeholder="Your name" 
              class="form-control" required="">
        </div>
      </div>

      <!-- Text input-->
      <div class="control-group">
        <label  for="Surname">Surname *</label>
        <div class="controls">
          <input class="width-100 <?php if ($errors->has('surname')) echo 'input-error'; ?>" 
            id="surname" 
            name="surname" 
            value="{{$user->last_name or Input::old('surname')}}" 
            type="text" 
            placeholder="Your surname" 
            required="">
          
        </div>
      </div>

      <!-- Text input-->
      <div class="control-group">
        <label  for="email">Email *</label>
        <div class="controls">
          <input class="width-100 <?php if ($errors->has('email')) echo 'input-error'; ?>" 
              id="email" 
              name="email" 
              value="{{$user->email or Input::old('email')}}" 
              type="text" 
              placeholder="Your email"  
              required="">
          
        </div>
      </div>

      <!-- Select box -->
      <div class="control-group">
        <label  for="email">Please help us by specifying one of the following: </label>
        <div class="controls">
          <?php
          echo Form::select('issue', 
                    array('problem' => 'I want to report a technical issue',
                          'question' => 'I have a question',
                          'feedback' => 'I want to give feedback',
                          ), 'problem');
          ?>
        </div>
      </div>

      <!-- Textarea -->
      <div class="control-group">
        <label  for="message">Message *</label>
        <div class="controls">                     
          <textarea class="form-control <?php if ($errors->has('message')) echo 'input-error'; ?>" 
              rows="5" 
              id="message" 
              name="message"
              equired="">{{Input::old('message')}}</textarea>
        </div>
      </div>

    <div class="control-group breathe">
      <label  for="singlebutton"></label>
      <div class="controls">
        <button class="btn">Send&nbsp;<i class="fa fa-share"></i></button>

      </div>
    </div>

    </fieldset>
  </div>
</div>
{{ Form::close() }}
<?php 
if ($errors->has('recaptcha_response_field') ) {
?>
<script>
    $('#recaptcha_response_field').removeAttr('style');
    $('#recaptcha_response_field').addClass('error');
</script>
<?php 
}
?>
@stop
