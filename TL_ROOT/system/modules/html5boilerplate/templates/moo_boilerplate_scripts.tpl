<!-- scripts concatenated and minified via ant build script-->
<script src="system/modules/html5boilerplate/html/js/plugins.js"></script>
<script src="system/modules/html5boilerplate/html/js/script.js"></script>
<!-- end concatenated and minified scripts-->

<!--[if lt IE 7 ]>
  <script src="system/modules/html5boilerplate/html/js/libs/dd_belatedpng.js"></script>
  <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
<![endif]-->


<?php if($GLOBALS['TL_CONFIG']['debugMode']): /* include profiler when debugging is enabled */ ?>
<!-- yui profiler and profileviewer - remove for production -->
<script src="system/modules/html5boilerplate/html/js/profiling/yahoo-profiling.min.js"></script>
<script src="system/modules/html5boilerplate/html/js/profiling/config.js"></script>
<!-- end profiling code -->
<?php endif; ?>