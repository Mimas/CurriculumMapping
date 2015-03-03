@extends('layouts.naked')
@section('content')
<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 24/10/2014
 * Time: 15:59
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>VFS</title>

    <!-- jQuery and jQuery UI (REQUIRED) -->
  <script>
    jQuery.browser = {};
    (function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
      jQuery.browser.msie = true;
      jQuery.browser.version = RegExp.$1;
    }
    })();
  </script>

    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('3rdparty/elfinder/css/elfinder.min.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('3rdparty/elfinder/css/theme.css');?>">

    <!-- elFinder JS (REQUIRED) -->
    <script src="<?php echo asset('3rdparty/elfinder/js/elfinder.min.js');?>"></script>


    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
      // Documentation for client options:
      // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
      $(document).ready(function() {
        $('#elfinder').elfinder({
          url : '<?php echo asset('3rdparty/elfinder/'); ?>/php/connector.php?root=<?php echo htmlspecialchars($vfs)?>',
          commands : [
            'open', 'reload', 'quicklook',
            'archive', 'search', 'info', 'view',
            'mkdir', 'upload',
            'help'

          ]
        });
      });
    </script>
  </head>
  <body>
    <!-- Element where elFinder will be created (REQUIRED) -->
    <div id="elfinder"></div>

  </body>
</html>
@stop
