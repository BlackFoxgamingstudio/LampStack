<div id="dashboard3">

	<?php if ($D->i_am_network_admin) { ?>
	<div class="mrg20B">
		<a href="<?php echo $C->SITE_URL?>admin" class="undecorated"><div class="byellow rounded"><?php echo $this->lang('dashboard_opc_admin');?></div></a>
    </div>
    <?php } ?>
    
    <?php if ($D->showaccessdirect) { ?>
	<div id="accesdirect">
    	<a href="<?php echo $C->SITE_URL?>dashboard/myitems" title="<?php echo $this->lang('dashboard_myitems_alt_myphotos')?>"><span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomyitems.png"></span></a>
        <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2" title="<?php echo $this->lang('dashboard_myitems_alt_addphoto')?>"><span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddphoto.png"></span></a>
        <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3" title="<?php echo $this->lang('dashboard_myitems_alt_addvideo')?>"><span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddvideo.png"></span></a>        
    </div>
    <?php } ?>
    
	<a href="<?php echo $C->SITE_URL?>directory/items" class="undecorated">
	<div id="linkdirectoryphotos" class="mrg20B">
    	<div class="centered"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/directorymedia.png" /></div>
        <div class="centered txtsize02"><?php echo $this->lang('global_directory_items_title')?></div>
    </div>
    </a>

	<div class="mrg20B">
		<div class="titleblock"><?php echo $this->lang('dashboard_more_users'); ?></div>
        
        <?php if (empty($D->useraccesories)) {?>
        
        	<div class="pdn10 centered"><?php echo $this->lang('dashboard_more_users_empty')?></div>
        	<hr>
            
        <?php } else { ?>
        
        	<div id="useraccesories"><?php echo $D->useraccesories?></div>
        
        <?php }?>
        
        <div id="linkmoreuser" class="linkblue"><a href="<?php echo $C->SITE_URL?>directory/people"><?php echo $this->lang('dashboard_more_users_linkviewmore')?></a></div>
        <hr>
        
    </div>
    
	<?php if (!empty($D->adsDashboard1)) { ?>
	<div class="mrg20B centered">
		<span><?php echo stripslashes($D->adsDashboard1) ?></span>
    </div>
    <?php } ?>

	<?php if (!empty($D->adsDashboard2)) { ?>
	<div class="mrg20B centered">
		<span><?php echo stripslashes($D->adsDashboard2) ?></span>
    </div>
    <?php } ?>

</div>