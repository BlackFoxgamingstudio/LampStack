<div class="oneactivity">
	<div class="oaheader">
    	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->userName?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->userAvatar)?$C->AVATAR_DEFAULT:$D->userAvatar)?>" class="rounded"></a></div>
        <div class="info">
        	<div class="iuser">
            	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->userName?>"><?php echo $D->nameUser?></a></span>
				<?php if ($D->isThisUserVerified0 == 1) { ?>
                <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
                <?php } ?>
            </div>
        	<div class="idate"><?php echo dateago($D->f_date)?></div>
        </div>
        <div class="sh"></div>	
    </div>
    
	<div class="oabody">
    	<div class="space01"><?php echo $D->txtaction?></div>
    	<div class="space02">
        	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->f_username?>"><?php echo $D->f_name?></a></span>
			<?php if ($D->isThisUserVerified == 1) { ?>
            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
            <?php } ?>
        </div>
    	<div class="space03"><a href="<?php echo $C->SITE_URL.$D->f_username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min1/'.(empty($D->f_avatar)?$C->AVATAR_DEFAULT:$D->f_avatar)?>" class="rounded"></a></div>
    	<div class="space04"><span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->f_username?>/photos"><?php echo ($D->f_numitems==1?$this->lang('dashboard_activities_hasphoto', array('#NUM#'=>$D->f_numitems)):$this->lang('dashboard_activities_hasphotos', array('#NUM#'=>$D->f_numitems)))?></a></span></div>
    </div>
</div>