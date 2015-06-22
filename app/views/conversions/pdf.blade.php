<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 24/10/2014
 * Time: 15:59
 */
?>
<iframe id="viewer" class="autoHeight" src = "/3rdparty/ViewerJS/index.html/#../../resourcecache/{{$file}}" width="100%" height="200" allowfullscreen webkitallowfullscreen></iframe>
<script>
  $('#viewer').load(function () {
    $(this).height($(window).height());
  });
</script>