<?php
$this->load_template('_home-header.php');
$this->load_template('_top.php');
?>
<div id="infohome">
	<div id="container">
    
<?php
$miniuser = array();
for($i = 0; $i < $D->numUserAleat; $i++) {
	$miniuser[$i]='';
	if (isset($D->userAleat[$i])) $miniuser[$i]='<a href="'.$C->SITE_URL.$D->userAleat[$i]->username.'/items"><img src="'.$C->SITE_URL.$C->FOLDER_AVATAR.'min1/'.(empty($D->userAleat[$i]->avatar)?$C->AVATAR_DEFAULT:$D->userAleat[$i]->avatar).'"></a>';
}
?>

    	<div id="usersAleat">
        	
        	<div class="mrg20T">
				<?php for($i = 0; $i < $D->numUserAleat; $i++) { ?>
                <div class="spacephoto">
                	<div class="oneminiphoto"><?php echo $miniuser[$i]?></div>
                </div>
                <?php } ?>
                <div class="sh"></div>
            </div>
        </div>    
    
		<div id="msghome" class="centered">
            <div class="title"><?php echo $this->lang('home_txt_welcome')?></div>
            <div class="subtitle"><?php echo $this->lang('home_txt_welcome_line1')?></div>
            <div class="subtitle"><?php echo $this->lang('home_txt_welcome_line2')?></div>
		</div>
        
        
        <div id="blockitems">
        
<?php
$miniphoto = array();
for($i = 0; $i < $D->numphotosrecent; $i++) {
	$miniphoto[$i]='';
	if (isset($D->photosRecents[$i])) $miniphoto[$i]='<a href="'.$C->SITE_URL.$D->photosRecents[$i]->username.'/items/'.$D->photosRecents[$i]->code.'" title="'.stripslashes($D->photosRecents[$i]->title).'"><img src="'.$C->SITE_URL.$C->FOLDER_PHOTOS.'/min2/'.$D->photosRecents[$i]->imageitem.'"></a>';
}
?>
        
            <div id="blockrecent">
            	<div class="title"><?php echo $this->lang('home_txt_recent_photos');?></div>
    			<div class="images">
					<?php for($i = 0; $i < $D->numphotosrecent; $i++) { ?>
                    <div class="onemini"><?php echo $miniphoto[$i]?></div>
                    <?php } ?>
                    <div class="sh"></div>
                </div>
                <div class="more"><span class="linkblue3"><a href="<?php echo $C->SITE_URL?>directory/items/t:1"><?php echo $this->lang('home_txt_more_photos');?></a></span></div>
            </div>
            
            
<?php
$miniphoto = array();
for($i = 0; $i < $D->numgifrecent; $i++) {
	$miniphoto[$i]='';
	if (isset($D->gifRecents[$i])) $miniphoto[$i]='<a href="'.$C->SITE_URL.$D->gifRecents[$i]->username.'/items/'.$D->gifRecents[$i]->code.'" title="'.stripslashes($D->gifRecents[$i]->title).'"><img src="'.$C->SITE_URL.$C->FOLDER_PHOTOS.'/min2/'.$D->gifRecents[$i]->imageitem.'"></a>';
}
?>          
            
            <div id="blockrecent">
            	<div class="title"><?php echo $this->lang('home_txt_recent_gif');?></div>
    			<div class="images">
					<?php for($i = 0; $i < $D->numgifrecent; $i++) { ?>
                    <div class="onemini"><?php echo $miniphoto[$i]?></div>
                    <?php } ?>
                    <div class="sh"></div>
                </div>
    			<div class="more"><span class="linkblue3"><a href="<?php echo $C->SITE_URL?>directory/items/t:2"><?php echo $this->lang('home_txt_more_gifs');?></a></span></div>
            </div>
            
            
<?php
$miniphoto = array();
for($i = 0; $i < $D->numvideosrecent; $i++) {
	$miniphoto[$i]='';
	if (isset($D->videosRecents[$i])) $miniphoto[$i]='<a href="'.$C->SITE_URL.$D->videosRecents[$i]->username.'/items/'.$D->videosRecents[$i]->code.'" title="'.stripslashes($D->videosRecents[$i]->title).'"><img src="'.$C->SITE_URL.$C->FOLDER_PHOTOS.'/min2/'.$D->videosRecents[$i]->imageitem.'"></a>';
}
?>  
            
            <div id="blockrecent">
            	<div class="title"><?php echo $this->lang('home_txt_recent_videos');?></div>
    			<div class="images">
					<?php for($i = 0; $i < $D->numvideosrecent; $i++) { ?>
                    <div class="onemini"><?php echo $miniphoto[$i]?></div>
                    <?php } ?>
                    <div class="sh"></div>
                </div>
            <div class="more"><span class="linkblue3"><a href="<?php echo $C->SITE_URL?>directory/items/t:3"><?php echo $this->lang('home_txt_more_videos');?></a></span></div>
            </div>
            
            <div class="sh"></div>
        </div>

	</div>
</div>


<?php
$this->load_template('_home-foot.php');
$this->load_template('_footer.php');
?>