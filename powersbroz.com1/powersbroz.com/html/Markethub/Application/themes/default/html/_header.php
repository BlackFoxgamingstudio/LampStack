<?php

	$this->user->write_pageview();
	
	//$this->load_langfile('inside/header.php');
?>

<!DOCTYPE html>
<!-- HTML5 Mobile Boilerplate -->
<!--[if IEMobile 7]><html class="no-js iem7"><![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--><html class="no-js" lang="en"><!--<![endif]-->

<!-- HTML5 Boilerplate -->
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo (isset($D->page_title)?$D->page_title:$C->SITE_TITLE); ?></title>
	<meta name="description" content="<?php echo (isset($D->site_description)?$D->site_description:$C->SITE_TITLE); ?>">
	<meta name="keywords" content="<?php echo (isset($D->site_keywords)?$D->site_keywords:$C->SITE_TITLE); ?>">
	<meta name="author" content="Santos Montano B.">
	<meta http-equiv="cleartype" content="on">
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese' rel='stylesheet' id="google_font_open_sans-css" type='text/css' media="all">
	<link rel="stylesheet" href="<?php echo $C->SITE_URL ?>themes/default/css/css.css" media="all">
	<base href="<?php echo $C->SITE_URL ?>" />
	<script type="text/javascript"> var siteurl = "<?php echo $C->SITE_URL ?>"; </script>
	<script src="<?php echo $C->SITE_URL ?>themes/default/js/modernizr-2.5.3-min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo $C->SITE_URL ?>themes/default/js/jquery-2.0.0.min.js"><\/script>')</script>
    <script src="<?php echo $C->SITE_URL ?>themes/default/js/js_basic.js"></script>
    <?php require_once('_analytics.php');?>

</head>
<body>
<div id="wrapper">