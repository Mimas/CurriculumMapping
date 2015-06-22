<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
  <meta charset="UTF-8">
  <title>Metadata Mapping Tool</title>
  <style>
    @import url(//fonts.googleapis.com/css?family=Lato:700);
    body {
      margin:0;
      font-family:'Lato', sans-serif;
      color: #999;
    }
    .container {
      padding: 7px;
    }
    .units-row {
      margin-bottom: -11px !important;
    }
    label {
      display: block;
      font-weight: 900;
    }
    .jisc {
      color: #ea6000;
      display: inline !important;
    }
    .norman {
      display: inline !important;
    }

    .breathe {
      margin-top: 12px;
    }

    #format { margin-top: 2em; }

    input[type='checkbox'] {
      display: none !important;
    }
    input[type='radio'] {
      display: none !important;
    }
    .ui-state-active {
      background: #2575ed !important;
    }
  </style>
  <link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
  <link rel="stylesheet" href="/3rdparty/jquery/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
  <link href="/3rdparty/tagit/css/jquery.tagit.css" rel="stylesheet" type="text/css">
  <link href="/3rdparty/tagit/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
  <script src="<?php echo (asset('3rdparty/tagit/js/tag-it.js')); ?>" type="text/javascript" charset="utf-8"></script>

  <link rel="stylesheet" href="<?php echo asset('3rdparty/font-awesome/css/font-awesome.min.css'); ?>">
  <?php
  if (isset($status['close'])) {
    ?>
    <script>
      parent.location.reload();
      parent.$.fn.colorbox.close();
    </script>
  <?php
  }
  ?>
  <script>
    $(function() {
      $(window).bind(
          "beforeunload", 
          function() { 
            $.ajax('/stoptracking/{{$trackingId}}', {
                  success: function(data) {
                  },
                  error: function() {
                  }
               });

          }
      );

      $(".checks" ).buttonset();
      $(".radio" ).buttonset();
      $('#obsolete4').change(function() {
          if($(this).is(":checked")) {
            $('#other_comments').removeAttr('disabled');
            $('#other_comments').css('color', '#000000');
          } else {
            $('#other_comments').attr('disabled', 'yes');
            $('#other_comments').css('color', '#ffffff');
          }
        });

      $('input[type=radio][name=currency]').on('change', function(){
        var flag = $(this).val();
        if (flag == 0) {
          $('#current').hide();
          $('#noncurrent').show();
        } else {
          $('#current').show();
          $('#noncurrent').hide();
        }
      });
      <?php
      if (null == $data->currency || $data->currency) {
      ?>
      $('#noncurrent').hide();
      <?php
      } else {
      ?>
      $('#current').hide();
      <?php
      }
      ?>
    });
  </script>
</head>
<body>
  <div class="container">
    <form method="post" action="<?php echo asset('edit'); ?>" class="forms">
      <div class="units-row">
        <div class="unit-100">
          <h2 class="norman">Resource Editor - </h2><h2 class="jisc">{{$resource['_source']['summary_title'] or '' }}</h2>
        </div>
        <div class="unit-100 breathe">
          <label for="currency">
            Would you recommend this resource?
            <div class="radio breathe">
              <input type="radio" id="currency1" name="currency" value="1" <?php if($data->currency) echo 'checked="checked"'; ?> /> 
              <label for="currency1">Yes</label>
              <input type="radio" id="currency2" name="currency" value="0" <?php if(!$data->currency) echo 'checked="checked"'; ?>/> 
              <label for="currency2">No</label>
            </div>
          </label>
          <p/>
        </div>
      </div>
      <div id="noncurrent">
        <div class="units-row">
          <div class="unit-100">
            <div class="checks" id="indicate">
              <label>Please choose one or more from the following options</label>
              <input type="checkbox" id="obsolete1" value="Out of Date" {{$data->checked('Out of Date')}} name="issues[]"  /> <label for="obsolete1">Out of Date</label>
              <input type="checkbox" id="obsolete2" value="Wrong Subject Area" {{$data->checked('Wrong Subject Area')}} name="issues[]"  /> <label for="obsolete2">Wrong Subject Area</label>
              <input type="checkbox" id="obsolete3" value="Quality Issues" {{$data->checked('Quality Issues')}} name="issues[]"  /> <label for="obsolete3">Quality Issues</label>
              <input type="checkbox" id="obsolete4" value="Other" name="issues[]" {{$data->checked('Other')}} /> <label for="obsolete4">Other</label>
            </div>
          </div>
        </div>
        <div class="units-row">
          <div class="unit-100">
            <label for="If other, please specify">
              <textarea  class="width-100" rows="4" id="other_comments" name="other_comments">{{$data->otherText()}}</textarea>
            </label>
          </div>
        </div>
      </div>
      <div id="current">
        <div class="units-row">
          <div class="unit-100">
            <label for="subject_area">
              Subject area ({{  $resource['_source']['subject'][0]['id'] or "" }} / {{$resource['_source']['subject'][0]['name'][0]['value']}})
              <br/>
              <input name="tags" id="mySingleField" value="{{$resourceTags}}" > <!-- only disabled for demonstration purposes -->
              <input type="hidden" name="uid" value="<?php echo $data->uid; ?>" />
              <input type="hidden" name="uuid" value="<?php echo $data->uuid; ?>" />
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="subject_area">
              Add custom tags. Please enclose phrases in double quotes. use the tab key to set the tag.
              <input name="user_tags" id="user_tags" value="{{$resourceUserTags}}" > <!-- only disabled for demonstration purposes -->
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="content_usage">How would you use this content?<br/>
              <textarea class="width-100" rows="3" id="content_usage" name="content_usage">{{$data->content_usage}}</textarea>
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="other_resources">What other resources do you use?<br/>
              <textarea class="width-100" rows="3" id="other_resources" name="other_resources">{{$data->other_resources}}</textarea>
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="desired_content">What other content (in your subject area) do you want?<br/>
              <textarea class="width-100" rows="3" id="desired_content" name="desired_content">{{$data->desired_content}}</textarea>
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="level">
              Qualification Level
              <div class="checks">
                <?php
                if (isset($qualifications)) foreach ($qualifications as $qualification) {
                  ?>
                  <input type="checkbox" <?php if(in_array($qualification->id, $resourceQualifications)) echo 'checked="checked"' ?>
                         id="check_{{$qualification->id}}" name="qualification_{{$qualification->id}}"
                    />
                  <label for="check_{{$qualification->id}}">Level {{$qualification->level}}</label>
                <?php
                }
                ?>
              </div>
            </label>
          </div>
        </div>

        <div class="units-row">
          <div class="unit-100">
            <label for="other_qualifications">Other qualifications or comments<br/>
              <textarea class="width-100" rows="3" id="other_qualifications" name="other_qualifications">{{$data->other_qualifications}}</textarea>
            </label>
          </div>
        </div>

      </div><!-- current -->
      <div class="units-row">
        <div class="unit-100 text-right">
          <button class="btn btn-small btn-yellow">Save <i class="fa fa-check"></i> </button>
        </div>
      </div>


    </form>
  </div> <!-- container -->
<script>
  var subjectTags = <?php if (isset($tags)) echo($tags) ?>;
  var userTags = <?php if (isset($userTags)) echo($userTags) ?>;

  $('#mySingleField').tagit({
    availableTags: subjectTags,
    showAutocompleteOnFocus: true
    // This will make Tag-it submit a single form value, as a comma-delimited field.
  });

  $('#user_tags').tagit({
    availableTags: userTags
    // This will make Tag-it submit a single form value, as a comma-delimited field.
  });

</script>
</body>
</html>
