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
	</style>
  <link rel="stylesheet" href="<?php echo asset('3rdparty/jquery'); ?>/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
  	<link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
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
        $( "#radio" ).buttonset();
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
                        <textarea class="width-100" rows="5" id="currency" name="currency">{{$data->currency}}</textarea>
                    </label>
                    <label for="subject_area">
                        Subject area<br/>
                        <textarea class="width-100" rows="5" id="subject_area" name="subject_area">{{$data->subject_area}}</textarea>
                        <input type="hidden" name="uid" value="<?php echo $data->uid; ?>"
                        <input type="hidden" name="uuid" value="<?php echo $data->uuid; ?>"
                    </label>
                    <label for="content_usage">
                        How would you use this content<br/>
                        <textarea class="width-100" rows="5" id="content_usage" name="content_usage">{{$data->content_usage}}</textarea>
                    </label>
                    <label for="level">
                        Qualification Levels<p/>
                        <div id="radio">
                          <input type="radio" id="radio1" name="radio"><label for="radio1">SCQF 3</label>
                          <input type="radio" id="radio2" name="radio"><label for="radio2">SCQF 4</label>
                          <input type="radio" id="radio3" name="radio"><label for="radio3">SCQF 5</label>
                        </div>

                    </label>
                </div>
                <div class="unit-100 text-right">
                    <button class="btn btn-small btn-yellow">Save <i class="fa fa-check"></i> </button>
                </div>
            </div>
        </div>

   </form>
</body>
</html>
