<?php
$cadaccion = '';
switch ($D->n_typenotifications) {
	case 1:
		$D->txtaction = $this->lang('global_txt_follow');
		break;	
	case 2:

		$urlphoto = $C->SITE_URL.$this->user->info->username.'/items/'.$D->n_photocode;
		$D->txtaction = $this->lang('global_txt_like').' <span class="linkblue"><a href="'.$urlphoto.'">'.$this->lang('global_txt_photo').'</a></span>.';
		break;	
	case 3:
		$urlphoto = $C->SITE_URL.$this->user->info->username.'/items/'.$D->n_photocode;
		$D->txtaction = $this->lang('global_txt_comment').' <span class="linkblue"><a href="'.$urlphoto.'">'.$this->lang('global_txt_photo').'</a></span>.';
		break;				
}
?>
	<div class="itemonenot">
		<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->n_username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->n_avatar)?$C->AVATAR_DEFAULT:$D->n_avatar)?>" class="rounded"></a></div>
		<div class="info">
        	<div class="txtaction"><span class="linkblue2 bold"><a href="<?php echo $C->SITE_URL.$D->n_username?>"><?php echo $D->n_nameUser?></a></span> <?php echo $D->txtaction?></div>
            <div class="txtwhen"><?php echo dateago($D->n_fdate)?></div>
        </div>
        <div class="sh"></div>
	</div>