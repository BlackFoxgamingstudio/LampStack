<div class="onecomment">

	<div class="photo"><a href="<?php echo $C->SITE_URL.$D->g->username.'/items/'.$D->g->pcode?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min2/'.$D->g->imageitem?>" class="edge5" title="<?php echo $D->g->title?>"></a></div>
    
    <div class="info">
        <div class="message"><?php echo analyzeMessage($D->g->comment) ?></div>
        <div class="whend"><?php echo dateago($D->g->whendate) ?></div>
    </div>
    <div class="sh"></div>
    
</div>
<div class="sh"></div>