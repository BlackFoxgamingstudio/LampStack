<div class="spacephoto">
	<div class="oneminiphoto">
		<a href="<?php echo $C->SITE_URL.$D->g->username.'/items/'.$D->g->code?>" title="<?php echo $D->g->title ?>">
        	<img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'/min1/'.$D->g->imageitem?>">
            <span class="typeitem"><?php echo $D->typeitem ?></span>
        </a>
     </div>
    <div class="moreinfo">
        <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icofav.png"> <?php echo($D->g->numlikes>1000?floor($D->g->numlikes/1000).'K':$D->g->numlikes)?></span>
        <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icocomment.png"> <?php echo($D->g->numcomments>1000?floor($D->g->numcomments/1000).'K':$D->g->numcomments)?></span>
        <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoview.png"> <?php echo($D->g->numviews>1000?floor($D->g->numviews/1000).'K':$D->g->numviews)?></span>
    </div>            	
</div>