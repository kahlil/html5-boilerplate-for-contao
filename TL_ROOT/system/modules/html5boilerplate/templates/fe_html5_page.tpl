<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<!-- Contao meta tags -->
	<base href="<?php echo $this->base; ?>">
	<title><?php echo $this->pageTitle; ?> - <?php echo $this->mainTitle; ?></title>
	<meta http-equiv="Content-Style-Type" content="text/css"/>
	<meta http-equiv="Content-Script-Type" content="text/javascript"/>
	<meta name="description" content="<?php echo $this->description; ?>"/>
	<meta name="keywords" content="<?php echo $this->keywords; ?>"/>
	
  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

	<?php echo $this->robots; ?>
	<?php echo $this->head; ?>
	<?php echo $this->framework; ?>
  <!-- CSS: implied media="all" -->
	<link rel="stylesheet" href="system/modules/html5boilerplate/html/css/style.css"/>
	<?php echo $this->stylesheets; ?>
	<!-- CSS for fancybox -->
	<link rel="stylesheet" href="system/modules/html5boilerplate/html/css/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	
	<!-- All JavaScript at the bottom, except for Modernizr and Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries -->
  <script src="system/modules/html5boilerplate/html/js/libs/modernizr-1.7.min.js"></script>
  <script src="system/modules/html5boilerplate/html/js/libs/respond.min.js"></script>
</head>

<body id="top"<?php if ($this->class): ?> class="<?php echo $this->class; ?>" <?php endif; if ($this->onload): ?> onload="<?php echo $this->onload; ?>"<?php endif; ?>>
	
<div id="wrapper">
<?php if ($this->header): ?>
<header>
	<div class="inside">
<?php echo $this->header; ?>
	</div>
</header>
<?php endif; ?>
<?php echo $this->getCustomSections('before'); ?>

<div id="container" class="clearfix">
<?php if ($this->left): ?>
<aside id="left">
	<div class="inside">
<?php echo $this->left; ?>
	</div>
</aside>
<?php endif; ?>
<?php if ($this->right): ?>
<aside id="right">
	<div class="inside">
<?php echo $this->right; ?>
	</div>
</aside>
<?php endif; ?>
<div id="main">
	<div class="inside">
<?php echo $this->main; ?>
	</div>
<?php echo $this->getCustomSections('main'); ?>
</div>
</div>
<?php echo $this->getCustomSections('after'); ?>

<?php if ($this->footer): ?>
<footer>
	<div class="inside">
<?php echo $this->footer; ?>
	</div>
</footer>
<?php endif; ?>

<!-- indexer::stop -->
<img src="<?php echo $this->base; ?>cron.php" alt="" class="invisible" />
<!-- indexer::continue -->

</div>

<?php echo $this->mootools; ?>

</body>
</html>
