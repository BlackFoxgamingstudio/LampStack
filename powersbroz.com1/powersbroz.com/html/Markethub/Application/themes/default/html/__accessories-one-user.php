<div class="one-user-accesories">
	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->acc_username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min3/'.(empty($D->acc_avatar)?'default.jpg':$D->acc_avatar); ?>" class="rounded"></a></div>
	<div class="infomsg">
    
		<div class="username linkblue">
        	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->acc_username?>"><?php echo $D->acc_name?></a></span>           
        </div>
        
		<div class="txtmsg"><?php echo $D->acc_numitems==1?$this->lang('profile_more_users_hasphoto', array('#NUM#'=>$D->acc_numitems)):$this->lang('profile_more_users_hasphotos', array('#NUM#'=>$D->acc_numitems)); ?></div>
        
    </div>

</div>