
<div id="footsite">

    <div id="container">
    
    	<?php if ($C->SHOW_MENU_PAGES) { ?>
    	<div><?php $this->load_template('__pagesmenu.php');?></div>
		<hr class="greye">
        <?php } ?>
    
    	<?php if ($C->SHOW_MENU_LANGUAJE) { ?>
    	<div><?php $this->load_template('__languagemenu.php');?></div>
		<hr class="greye">
        <?php } ?>
        
        <div id="foot-right">&copy; <?php echo date('Y'); ?> - <?php echo $C->SITE_TITLE; ?></div>
        
        <div class="sh"></div>
        
    </div>


</div>
<script>
	// When the window is focused
	$(window).focus(function() {
		// If the currentTitle has the (!) notification, then remove it
		if(document.title.indexOf('[!]') >= 0) {
			document.title = document.title.replace('[!] ', '');
		}
	});
</script>