<div class="oneactivity">
	<div class="oaheader">
    	<div class="avatar"><a href="<?php echo $C->SITE_URL.$D->u->username?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->u->avatar)?$C->AVATAR_DEFAULT:$D->u->avatar)?>" class="rounded"></a></div>
        <div class="info">
        	<div class="iuser"><span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->u->username?>"><?php echo $D->nameUser?></a></span></div>
        	<div class="idate"><?php echo dateago($D->a_date)?></div>
        </div>
        <div class="sh"></div>	
    </div>
    
	<div class="oabody">
    	<div class="space01"><?php echo $D->txtaction?></div>
        
    	<div class="space02 mrg10T">
        	<span style="position:relative;">
                <a href="<?php echo $C->SITE_URL.$D->u->username.'/items/'.$D->codephoto?>" title="<?php echo $D->title?>">
                <img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min1/'.$D->imageitem?>" class="edge8">
                <span class="typeitem"><?php echo $D->typeitem ?></span>
                </a>
            </span>
            
        </div>
        
    	<div class="space03">
            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icofav.png"> <?php echo($D->numlikes>1000?floor($D->numlikes/1000).'K':$D->numlikes)?></span>
            <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icocomment.png"> <?php echo($D->numcomments>1000?floor($D->numcomments/1000).'K':$D->numcomments)?></span>
            <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoview.png"> <?php echo($D->numviews>1000?floor($D->numviews/1000).'K':$D->numviews)?></span>
        </div>
        <div class="space04 mrg5T">
        	<div class="line1"><span><?php echo $this->lang('profile_activities_inalbum')?></span></div>
            <div class="line2 linkblue2 italica"><a href="<?php echo $C->SITE_URL.$D->u->username.'/items/folder/'.$D->codeAlbum?>"><?php echo $D->nameAlbum?></a></div>
        </div>
    	<div class="space05">

        </div>
    </div>
</div>