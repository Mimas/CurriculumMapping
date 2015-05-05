@extends('layouts.plain')
@section('content')
<div class="units-row top44">
    <div class="unit-100 text-right">
      <img alt="REDIS" title="REDIS logo" src="<?php echo asset('assets/img') ?>/redis.png" />
    </div>
</div>
<div class="units-row top44">
    <div class="unit-100 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
       <h3>Redis</h3>
        <div class="redis_edit">
          {{ Form::open(array('url' => 'redis/edit', 'class'=>'forms')) }}
          <label>
            Key
            <input type="text" name="key" id="key" class="width-80" value="{{$key}}" />
          </label>
          <label>
            TTL (-1 for eternity)
            <input type="text" name="ttl" id="ttl" class="width-80" value="{{$ttl}}" />
          </label>
          <label>
            Value
            <textarea name="value" id="value" rows="10" class="width-80">{{$data}}</textarea>
          </label>
          <?php
          echo Form::token();
          echo Form::close();
          ?>
        </div>
        <div>
          <ul class="horizontal" >
            <li><a class="btn btn-smaller ajax" id="djson" href="#">JSON decode</a></li>
            <li><a class="btn btn-smaller ajax" id="dephp" href="#">PHP unserialize</a></li>
            <li><a class="btn btn-smaller ajax" id="ejson" href="#">JSON encode</a></li>
            <li><a class="btn btn-smaller ajax" id="enphp" href="#">PHP serialize</a></li>
            <li><a class="btn btn-smaller btn-yellow ajax" id="update" href="#" >Update</a></li>
            <li><a class="btn btn-smaller ajax" id="refresh" href="#">Refresh</a></li>
          </ul>
        </div>
        <div id="message"></div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.ajax').click(function(e) {
        $('#message').html('clicked');
        // prevent the links default action
        // from firing
        e.preventDefault();
        // attempt to GET the new content
        $.ajax({
            type: 'POST',
            url: "<?php echo URL::to('redis') ?>/"+$(this).attr('id'),
            dataType: "json",
            data:{ key: $('#key').val(), ttl: $('#ttl').val(), data: $('#value').val() },
            success: function( data ) {
              if (data.error == '' ) {
                $('#value').val(data.data);
                if (typeof data.key != 'undefined') {
                  $('#key').val(data.key);
                }
                if (typeof data.ttl != 'undefined') {
                  $('#ttl').val(data.ttl);
                }
                $('#message').html(' ');
              } else {
                $('#message').html(data.error);
              }
            },
            failure: function( errMsg ) {
              alert('oops');
            }
        });
       //     $.post("<?php echo URL::to('redis') ?>/"+$(this).attr('id'), {data: $('#value').text(), key: '<?php echo($key); ?>'}, function(data) {
       //         alert(data);
       //        // $('#value').text(data);
       //     });
     })
 });
  </script>
@stop
