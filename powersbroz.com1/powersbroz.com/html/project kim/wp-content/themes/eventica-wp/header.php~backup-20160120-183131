<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie6 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html class="ie7 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie8 oldie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="site-container" class="site-container sb-site-container">
		<?php if ( function_exists('ubermenu') ) : ?>
			<?php get_template_part( 'block-header-ubermenu' ); ?>
		<?php else : ?>
			<?php get_template_part( 'block-header-menu' ); ?>
		<?php endif; ?>
