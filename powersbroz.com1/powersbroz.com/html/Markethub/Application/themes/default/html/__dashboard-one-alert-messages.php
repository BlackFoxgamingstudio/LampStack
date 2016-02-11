<div class="one-alert-msg-container">
	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->username?>/messages"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min3/'.(empty($D->avatar)?'default.jpg':$D->avatar); ?>" class="rounded"></a></div>
	<div class="infomsg">
		<div class="username linkblue"><a href="<?php echo $C->SITE_URL.$D->username?>/messages"><?php echo $D->uname?></a></div>
		<div class="txtmsg"><?php echo $D->message?></div>
		<div class="dateago"><?php echo dateago($D->dateago)?></div>        
    </div>

</div>