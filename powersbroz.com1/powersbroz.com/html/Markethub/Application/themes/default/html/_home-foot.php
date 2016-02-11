
<div id="footsite">

    <div id="container">
    
    	<?php if ($C->SHOW_MENU_PAGES) { ?>
    	<div><?php $this->load_template('__pagesmenu.php');?></div>
		<hr class="blue">
        <?php } ?>
    
        <?php if ($C->SHOW_MENU_LANGUAJE) { ?>
        <div><?php $this->load_template('__languagemenu.php');?></div>
        <hr class="blue">
        <?php } ?>

        <div id="foot-right">&copy; <?php echo date('Y'); ?> - <?php echo $C->SITE_TITLE; ?></div>
        
        <div class="sh"></div>            

    </div>

</div>