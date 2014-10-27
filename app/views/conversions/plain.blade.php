<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 24/10/2014
 * Time: 15:59
 */
?>
<script src="{{asset('3rdparty/syntaxhighlighter/scripts/shCore.js')}}"></script>
<script src="{{asset('3rdparty/syntaxhighlighter/scripts/shBrushXml.js')}}"></script>

<link rel="stylesheet" href="<?php echo asset('3rdparty/syntaxhighlighter/styles/shCore.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('3rdparty/syntaxhighlighter/styles/shThemeDefault.css'); ?>">

<pre class="brush: xml">
<?php echo str_replace('<', '&lt;', $bitstream->retrieveStream()); ?>
</pre>

<!-- Finally, to actually run the highlighter, you need to include this JS on your page -->
<script type="text/javascript">
  SyntaxHighlighter.all()
</script>
