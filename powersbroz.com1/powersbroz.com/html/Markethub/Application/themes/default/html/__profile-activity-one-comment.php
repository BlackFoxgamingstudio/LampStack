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
        	<a href="<?php echo $C->SITE_URL.$D->f_username.'/items/'.$D->codephoto?>" title="<?php echo $D->title?>">
        	<img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min1/'.$D->imageitem?>" class="edge8">
            <span class="typeitem"><?php echo $D->typeitem ?></span>
            </a>
            </span>
            
        </div>
        
    	<div class="space03">

        </div>
        <div class="space04 mrg5T">
			<div class="line3 italica"><?php echo analyzeMessage($D->txtcomment)?></div>
        </div>
    	<div class="space05">

        </div>
    </div>
</div>