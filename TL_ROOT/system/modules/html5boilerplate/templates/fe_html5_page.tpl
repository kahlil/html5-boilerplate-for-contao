<!doctype html>  

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html lang="<?php echo $this->language; ?>" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="<?php echo $this->language; ?>" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="<?php echo $this->language; ?>" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="<?php echo $this->language; ?>" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="<?php echo $this->language; ?>" class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

	<base href="<?php echo $this->base; ?>">
	<title><?php echo $this->pageTitle; ?> - <?php echo $this->mainTitle; ?></title>
	<meta http-equiv="Content-Style-Type" content="text/css"/>
	<meta http-equiv="Content-Script-Type" content="text/javascript"/>
	<meta name="description" content="<?php echo $this->description; ?>"/>
	<meta name="keywords" content="<?php echo $this->keywords; ?>"/>
	<!--  Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->robots; ?>
	<?php echo $this->head; ?>

	<?php echo $this->framework; ?>
	<link rel="stylesheet" href="layout/css/style.css?v=2"/>
	<?php echo $this->stylesheets; ?>
	
	<!-- Uncomment if you are specifically targeting less enabled mobile browsers
  <link rel="stylesheet" media="handheld" href="layout/css/handheld.css?v=2">  -->

	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <script src="system/layout/html5boilerplate/html/js/libs/modernizr-1.6.min.js"></script>

</head>

<body id="top"<?php if ($this->class): ?> class="<?php echo $this->class; ?>" <?php endif; if ($this->onload): ?> onload="<?php echo $this->onload; ?>"<?php endif; ?>>
	
<div id="wrapper">
<?php if ($this->header): ?>
<header>
	<section class="inside">
<?php echo $this->header; ?>
	</section>
</header>
<?php endif; ?>
<?php echo $this->getCustomSections('before'); ?>

<p id="jsnotice"> <!-- If you want to edit this through Contao (for different languages etc.) you will have to add a section that you can fill with an article.  -->
  Javascript is currently disabled. This site requires Javascript to function correctly.
  Please <a href="http://enable-javascript.com/"> enable Javascript in your browser</a>!
</p>

<div id="container" class="clearfix">
<?php if ($this->left): ?>
<aside id="left">
	<section class="inside">
<?php echo $this->left; ?>
	</section>
</aside>
<?php endif; ?>
<?php if ($this->right): ?>
<aside id="right">
	<section class="inside">
<?php echo $this->right; ?>
	</section>
</aside>
<?php endif; ?>
<div id="main">
	<section class="inside">
<?php echo $this->main; ?>
	</section>
<?php echo $this->getCustomSections('main'); ?>
</div>
</div>
<?php echo $this->getCustomSections('after'); ?>

<?php if ($this->footer): ?>
<footer>
	<section class="inside">
<?php echo $this->footer; ?>
	</section>
</footer>
<?php endif; ?>

<!-- indexer::stop -->
<img src="<?php echo $this->base; ?>cron.php" alt="" class="invisible" />
<!-- indexer::continue -->

</div>

<?php echo $this->mootools; ?>

</body>
</html>
