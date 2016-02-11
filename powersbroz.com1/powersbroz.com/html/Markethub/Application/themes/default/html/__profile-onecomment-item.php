<div id="oc_<?php echo $D->o_idcomment?>" class="onecomment">
	<?php if ($D->o_owner) { ?>
	<a onclick="deleteComments(<?php echo $D->o_idcomment?>, <?php echo $D->idItem ?>, <?php echo $D->idUser?>)"><div class="delete hand">x</div></a>
    <?php } ?>
    <div class="avatar"><a href="<?php echo $C->SITE_URL.$D->o_username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.$D->o_avatar?>" class="rounded"></a></div>
    <div class="info">
        <div class="user"><span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->o_username?>"><?php echo $D->o_nameUser?></a></span></div>
        <div class="message"><?php echo analyzeMessage($D->o_comment)?></div>
        <div class="whend"><?php echo dateago($D->o_whendate)?></div>
    </div>
    
</div>