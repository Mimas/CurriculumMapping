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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('3rdparty/font-awesome/css/font-awesome.min.css'); ?>">
    <?php
    if (isset($status['close'])) {
    ?>
    <script>
        parent.location.reload();
    </script>
    <?php
    }
    ?>
</head>
<body>
   <form method="post" action="<?php echo asset('ldc'); ?>" class="forms">
        <div class="container">
            <div class="units-row">
              <div class="unit-100">
                <h2 class="norman">Learn Direct Classification System Editor - </h2><h2 class="jisc">{{$data->ldcs_desc or '' }}</h2>
              </div>
                <div class="unit-100 breathe">
                    <label for="subject_area">
                        Learn Direct Code<br/>
                        <input type="text" class="width-100" id="ldcs_code" name="ldcs_code" value="{{$data->ldcs_code}}" />
                        <input type="hidden" name="id" value="<?php echo $data->id; ?>"
                    </label>
                </div>
                <div class="unit-100 breathe">
                    <label for="ldcs_desc">
                        Learn Direct Description<br/>
                        <textarea class="width-100" rows="5" id="ldcs_desc" name="ldcs_desc">{{$data->ldcs_desc}}</textarea>
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
