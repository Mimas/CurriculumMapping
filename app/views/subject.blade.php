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
    </script>
    <?php
    }
    ?>
</head>
<body>
   <form method="post" action="<?php echo asset('subject'); ?>" class="forms">
        <div class="container">
            <div class="units-row">
                <div class="unit-100">
                    <label for="subject_area">
                        Subject area<br/>
                        <textarea class="width-100" rows="5" id="subject" name="subject">{{$data->subject}}</textarea>
                        <input type="hidden" name="id" value="<?php echo $data->id; ?>"
                    </label>
                    <label for="extras">
                        Conditions<br/>
                        <textarea class="width-100" rows="5" id="stuff" name="stuff">{{$data->stuff}}</textarea>
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
