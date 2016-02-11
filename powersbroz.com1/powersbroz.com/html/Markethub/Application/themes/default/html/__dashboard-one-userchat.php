<div class="oneuserchat">
	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->f_username?>/messages"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->f_avatar)?'default.jpg':$D->f_avatar); ?>" class="rounded8"></a></div>
    <div class="info">
    	<div class="name">
        	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->f_username?>/messages"><?php echo $D->f_name?></a></span>
			<?php if ($D->isThisUserVerified) { ?>
            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
            <?php } ?>
        </div>
        <div class="moreinfo"><?php echo $D->f_lastmessage; ?></div>
        <div class="datewhen"><?php echo dateago($D->f_date); ?></div>
    </div>
    <div class="sh"></div>
	
</div>