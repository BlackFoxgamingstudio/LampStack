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
        	<div class="idate"><?php echo dateago($D->a_date)?></div>
        </div>
        <div class="sh"></div>	
    </div>
    
	<div class="oabody">
    	<div class="space01"><?php echo $D->txtaction?></div>
    	<div class="space02 mrg10T"><span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->userName.'/items/folder/'.$D->codealbum?>"><?php echo $D->name?></a></span></div>
    	<div class="space04 italica"><?php echo $D->description?></div>
        <div class="space03 mrg10T"><?php echo $this->lang('dashboard_activities_txtcontains').' '.$D->numitems.' '.($D->numitems==1?$this->lang('dashboard_activities_txtphoto'):$this->lang('dashboard_activities_txtphotos'))?></div>
    </div>
</div>