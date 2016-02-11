<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8 wp-toolbar"  lang="en-US">
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" class="wp-toolbar"  lang="en-US">
<!--<![endif]-->
<head>
	<title><?php echo __('Revolution Slider'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php foreach ($cssIncludes as $_css) : ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $_css; ?>" />
	<?php endforeach; ?>
	<?php foreach ($jsIncludes as $_js) : ?>
		<script type="text/javascript" src="<?php echo $_js; ?>"></script>
	<?php endforeach; ?>
	<script type='text/javascript'>
		/* <![CDATA[ */
		var wpColorPickerL10n = {"clear":"Clear","defaultString":"Default","pick":"Select Color","current":"Current Color"};
		var rev_lang = <?php echo $revLang; ?>;
		/* ]]> */
	</script>
	<?php if ($inlineStyles) : ?>
		<?php foreach ($inlineStyles as $_style) : echo $_style; endforeach; ?>
	<?php endif; ?>
	<?php echo $adminHead; ?>
</head>
<body class="wp-admin wp-core-ui">