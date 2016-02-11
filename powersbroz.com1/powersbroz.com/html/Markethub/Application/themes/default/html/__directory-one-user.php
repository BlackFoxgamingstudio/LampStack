<div class="one-user-directory">
	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->f_username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->f_avatar)?'default.jpg':$D->f_avatar); ?>" class="rounded"></a></div>
	<div class="infomsg">
    
		<div class="username linkblue">
        	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->f_username?>"><?php echo $D->f_name?></a></span>    
			<?php if ($D->isThisUserVerified) { ?>
            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
            <?php } ?>       
        </div>
        
		<div class="txtmsg"><?php echo $D->f_numitems==1?$this->lang('global_txt_directory_hasphoto', array('#NUM#'=>$D->f_numitems)):$this->lang('global_txt_directory_hasphotos', array('#NUM#'=>$D->f_numitems)); ?></div>
        
    </div>

</div>