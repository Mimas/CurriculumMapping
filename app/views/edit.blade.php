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
        parent.$.fn.colorbox.close();
    </script>
    <?php
    }
    ?>
</head>
<body>
   <form method="post" action="<?php echo asset('edit'); ?>" class="forms">
        <div class="container">
            <div class="units-row">
                <div class="unit-100">
                    <label for="subject_area">
                        Subject area<br/>
                        <textarea class="width-100" rows="5" id="subject_area" name="subject_area">{{$data->subject_area}}</textarea>
                        <input type="hidden" name="uid" value="<?php echo $data->uid; ?>"
                        <input type="hidden" name="uuid" value="<?php echo $data->uuid; ?>"
                    </label>
                    <label for="content_usage">
                        Content usage<br/>
                        <textarea class="width-100" rows="5" id="content_usage" name="content_usage">{{$data->content_usage}}</textarea>
                    </label>
                    <label for="level">
                        Level
                        <textarea class="width-100" rows="3" id="level" name="level">{{$data->level}}</textarea>
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
