<div id="msg_<?php echo $D->idmsg?>" class="prof-one-msgchat-container">
	<?php if ($D->iduser == $this->user->id) { ?>
	<a onclick="deletemsg(<?php echo $D->idmsg?>)"><div class="delete hand">x</div></a>
    <?php } ?>
	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min3/'.(empty($D->avatar)?'default.jpg':$D->avatar); ?>" class="rounded"></a></div>
	<div class="infomsg">
		<div class="username linkblue"><a href="<?php echo $C->SITE_URL.$D->username?>"><?php echo $D->uname?></a></div>
		<div class="txtmsg"><?php echo $D->message?></div>
		<div class="dateago"><?php echo dateago($D->dateago)?></div>        
    </div>

</div>