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
      margin-bottom: 7px !important;
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
        $( "#format" ).buttonset();
      });
    </script>
</head>
<body>
   <form method="post" action="<?php echo asset('edit'); ?>" class="forms">
        <div class="container">
            <div class="units-row">
                <div class="unit-100">
                    <h2 class="norman">Resource Editor - </h2><h2 class="jisc">{{$resource['_source']['summary_title'] or '' }}</h2>
                </div>
                <div class="unit-100 breathe">
                    <label for="currency">
                        Currency<br/>
                        <textarea class="width-100" rows="3" id="currency" name="currency">{{$data->currency}}</textarea>
                    </label>
                    <p/>
                    <label for="subject_area">
                        Subject area (<?php
                        if (isset($resource['_source']['subject']['ldcode'])) foreach( $resource['_source']['subject']['ldcode'] as $i=>$code ) {
                          ?>
                          {{ $code or '' }}, {{$resource['_source']['subject']['ld'][$i] or '' }}
                        <?php
                        }
                        ?>)
                      <br/>
                        <input name="tags" id="mySingleField" value="" > <!-- only disabled for demonstration purposes -->
                      </p>
                      <input type="hidden" name="uid" value="<?php echo $data->uid; ?> />"
                      <input type="hidden" name="uuid" value="<?php echo $data->uuid; ?> />"
                    </label>
                    <p/>
                    <label for="content_usage">
                        How would you use this content?<br/>
                        <textarea class="width-100" rows="3" id="content_usage" name="content_usage">{{$data->content_usage}}</textarea>
                    </label>
                  <p/>
                  <label for="level">
                        Qualification Levels<p/>
                    <div id="format">
                      <?php
                      foreach ($qualifications as $qualification) {
                      ?>
                        <input type="checkbox" id="check_{{$qualification->id}}"><label for="check_{{$qualification->id}}">Level {{$qualification->level}}</label>
                      <?php
                      }
                      ?>
                    </div>

                  </label>
                </div>
                <div class="unit-100 text-right">
                    <button class="btn btn-small btn-yellow">Save <i class="fa fa-check"></i> </button>
                </div>
            </div>
        </div>

   </form>
   <script>
     var sampleTags = <?php echo($tags) ?>;

     //-------------------------------
     // Minimal
     //-------------------------------

     //-------------------------------
     // Single field
     //-------------------------------
     $('#mySingleField').tagit({
       availableTags: sampleTags,
       showAutocompleteOnFocus: true
       // This will make Tag-it submit a single form value, as a comma-delimited field.
     });
   </script>
</body>
</body>
</html>
