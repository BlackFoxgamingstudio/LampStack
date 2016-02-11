<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1"><?php $this->load_template('_verticalmenu.php'); ?></div>
        
        <div id="column2">
		<?php
		$this->load_template('_profile-info-basic.php');
		
		if ($D->show_profile == 0) {
			$this->load_template('_profile-no-show.php');
		} else {
                        
		?>            

        	<div id="profile2">
            	<div id="myalbum">
                
                
                    <div>&laquo; <span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->usernameProfile.'/items'?>"><?php echo $this->lang('profile_items_txtviewmorealbums')?></a></span></div>
                
                	<div class="centered">
            
                        <div class="title"><?php echo $D->namealbum;?></div>
                        <div class="description"><?php echo $D->description;?></div>
                        
                        <div class="mrg10T">
                        
                            <span><a href="http://www.facebook.com/sharer.php?u=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/folder/'.$D->codalbum?>&t=<?php echo $C->SITE_TITLE?>&s=<?php echo $this->lang('global_txt_sharedalbum')?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedalbum')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icofacebook.png"></a>
                            </span>
                            <span>
                            	<a href="http://twitter.com/home?status=<?php echo $this->lang('global_txt_sharedalbum')?> <?php echo $C->SITE_URL.$D->usernameProfile.'/items/folder/'.$D->codalbum?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedalbum')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icotwitter.png"></a></span>
                            <span><a href="http://pinterest.com/pin/create/button/?url=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/folder/'.$D->codalbum?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedalbum')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icopinterest.png"></a></span>
                            <span><a href="http://www.linkedin.com/shareArticle?url=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/folder/'.$D->codalbum?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedalbum')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icolinkedin.png"></a></span>
                        
                        </div>
                
                	</div>            
            
            		<?php if (!empty($D->htmlPhotos)) { ?>
            		
					<div class="mrg20T"><?php echo $D->htmlPhotos; ?></div>
                    
                    <?php } else {?>
                    
                    <div class="mrg30T centered"><?php echo $this->lang('profile_items_txtalbumnophotos'); ?></div>
                    
                    <?php } ?>
                
                </div>
            </div>
            
        <?php } ?>
        </div>
        
        <div id="divseparator" class="sh"></div>
        
        <div id="column3"><?php $this->load_template('_accessories.php'); ?></div>
        
        <div class="sh"></div>
    
    </div>
            
</div>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>