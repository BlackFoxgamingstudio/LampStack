<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo __('Standalone Slider Revolution Installation'); ?></title>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/account.css" type="text/css" />
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&subset=latin%2Clatin-ext" />
</head>
<body>
	<div class="panel">
		<div class="header">
			<img src="<?php echo base_url(); ?>assets/images/logo_small.png" alt="<?php echo __('Slider Revolution'); ?>" />
			<div class="version"><?php echo __('Version'); ?> <?php echo $version ?></div>
		</div>
		<?php echo $view_html; ?>
	</div>
</body>
</html>