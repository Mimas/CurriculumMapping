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
        ul.horizontal
        {
          margin-left: 0px;
          list-style: none !important;
          text-align: left !important;
        }
        ul.horizontal li {
          display: inline;
        }
      .jisc {
        color: #ea6000;
      }

    </style>
    <?php
    if (isset($status['close'])) {
      ?>
      <script>
        parent.$.fn.colorbox.close();
      </script>
    <?php
    }
    ?>

    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>

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
@yield('content')
</body>
</html>
