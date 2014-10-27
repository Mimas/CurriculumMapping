<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 24/10/2014
 * Time: 15:59
 */
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="100%">
  <param name="movie" value="{{asset('resourcecache/'.$file)}}" />
  <param name="quality" value="high" />
  <PARAM name="scale" VALUE="default">
  <embed src="{{asset('resourcecache/'.$file)}}" quality="high" type="application/x-shockwave-flash" width="100%" height="100%" SCALE="default" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>