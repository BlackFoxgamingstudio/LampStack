<div class="oneactivity">
	<div class="oabody">
    	<div class="space03 mrg10T"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min1/'.(empty($D->u->avatar)?$C->AVATAR_DEFAULT:$D->u->avatar)?>" class="rounded"></div>
    	<div class="space02">
        	<span><?php echo $D->nameUser?></span>
			<?php if ($D->isUserVerified == 1) { ?>
            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
            <?php } ?>
        </div>
    	<div class="space04 mrg10T"><?php echo $this->lang('global_txt_joined', array('#SITE_TITLE#'=>$C->SITE_TITLE))?> <?php echo date($this->lang('global_format_date'),$D->u->registerdate)?></div>
    </div>
</div>