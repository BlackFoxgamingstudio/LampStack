<div class="one-user">
	<div class="avatar"><a href="<?php echo $C->SITE_URL.'admin/users/u:'.$D->f_username.'/p:'.$D->npage?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min3/'.(empty($D->f_avatar)?'default.jpg':$D->f_avatar); ?>" class="rounded"></a></div>
	<div class="infomsg">
    
		<div class="username linkblue">
        	<span class="linkblue2"><a href="<?php echo $C->SITE_URL.'admin/users/u:'.$D->f_username.'/p:'.$D->npage?>"><?php echo $D->f_name?></a></span>           
        </div>
        
		<div class="txtmsg"><?php echo($D->f_admin==1?$this->lang('admin_manageuser_txtadministrator'):'')?></div>
        <div class="txtmsg"><?php echo($D->f_validated==1?$this->lang('admin_manageuser_txtisvalidated'):'')?></div>
    </div>

</div>