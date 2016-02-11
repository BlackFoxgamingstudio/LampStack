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


    	<div class="space02 mrg10T">

        	<span style="position:relative;">
        	<a class="img_<?php echo $D->codephoto?>" href="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->imageitem?>" title="<?php echo $D->title?>">
        	<img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min1/'.$D->imageitem?>" class="edge8">
            <span class="typeitem"><?php echo $D->typeitem ?></span>
            </a>
            </span>
            
        </div>

        <div class="space04 mrg5T">
        	<div class="line3 italica linkblue2"><a href="<?php echo $C->SITE_URL.$D->f_username.'/items/'.$D->codephoto?>"><?php echo $D->txtcomment?></a></div>
        	
        </div>


    </div>
</div>
<script>

	$('.img_<?php echo $D->codephoto?>').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

</script>