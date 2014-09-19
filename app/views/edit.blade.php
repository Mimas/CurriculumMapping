<!doctype html>
<html lang="en">
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

    <link rel="stylesheet" href="<?php echo asset('3rdparty/colorbox/colorbox.css'); ?>"/>
    <script src="<?php echo asset('3rdparty/colorbox/jquery.colorbox.js');?>"></script>

    <script>
        $(document).ready(function(){
            $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
        });
    </script>
</head>
<body>
   <form method="post" action="/" class="forms">
        <div class="container">
            <div class="units-row">
                <div class="unit-100">
                <label for="subject_area">
                    Subject area<br/>
                    <textarea class="width-80" rows="5" id="subject_area" name="subject_area">{{$data->subject_area}}</textarea>
                </label>
                <label for="content_usage">
                    Content usage<br/>
                    <textarea class="width-80" rows="5" id="content_usage" name="content_usage">{{$data->content_usage}}</textarea>
                </label>


                </div>
            </div>
        </div>

   </form>
</body>
</html>
