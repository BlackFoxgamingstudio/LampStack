<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_profile.js"></script>
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
            
            	<div id="photodetails">
                	
                    <div>&laquo; <span class="linkblue2"><a href="<?php echo $C->SITE_URL.$D->usernameProfile.'/items/folder/'.$D->codeAlbum?>"><?php echo $this->lang('profile_items_txtviewmorephotos')?></a></span></div>
                
                	<div class="centered">
            
                        <div class="title"><?php echo $D->selectedPhoto->title;?></div>
                        <div class="description"><?php echo $D->selectedPhoto->description;?></div>
                        <div class="mrg10T">
                            
                            <span><a href="http://www.facebook.com/sharer.php?u=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->codeItem?>&t=<?php echo $C->SITE_TITLE?>&s=<?php echo $this->lang('global_txt_shared')?>&i=<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min2/'.$D->selectedPhoto->imageitem?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedphoto')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icofacebook.png"></a>
                            </span>
                            <span><a href="http://twitter.com/home?status=<?php echo $this->lang('global_txt_shared')?> <?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->codeItem?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedphoto')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icotwitter.png"></a></span>
                            <span><a href="http://pinterest.com/pin/create/button/?url=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->codeItem?>&media=<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->selectedPhoto->imageitem?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedphoto')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icopinterest.png"></a></span>
                            <span><a href="http://www.linkedin.com/shareArticle?url=<?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->codeItem?>" target="_blank" title="<?php echo $this->lang('global_txt_altsharedphoto')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icolinkedin.png"></a></span>
                            
                        </div>
                            
                        <div>
                        	<?php if (!empty($D->prevnext[0])) {?>
                        	<span class="fleft linkblue"><a href="<?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->prevnext[0]?>" title="<?php echo $this->lang('global_txt_prev')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoprev.png"></a></span>
                            <?php } ?>
                            
                        	<?php if (!empty($D->prevnext[1])) {?>
                        	<span class="fright linkblue"><a href="<?php echo $C->SITE_URL.$D->usernameProfile.'/items/'.$D->prevnext[1]?>" title="<?php echo $this->lang('global_txt_next')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/iconext.png"></a></span>
                            <?php } ?>
                        </div>    
                            
                        <div class="mrg10T">
                        	<?php if ($D->selectedPhoto->typeitem == 3) { ?>
                            
                            <div class="blackvideo">
                            
                            	<div class="interno">
                                
									<iframe style="visibility: visible;" onload="this.style.visibility='visible';" src="http://www.youtube.com/embed/<?php echo $D->selectedPhoto->codvideo?>?showinfo=0&amp;autoplay=1&amp;modestbranding=1&amp;autohide=1&amp;rel=0&amp;fs=1&amp;loop=0&amp;controls=1&amp;theme=dark&amp;iv_load_policy=3&amp;wmode=opaque" frameborder="0" allowfullscreen=""></iframe>

                                </div>
                            
                            </div>
                            <div class="sh mrg20T"></div>
                            
                            <?php } else { ?>
                        	
                            <div><img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->selectedPhoto->imageitem?>"></div>
                            <div id="urlphoto" class="mrg10B"><?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->selectedPhoto->imageitem?></div>
                            
							<?php } ?>
                        </div>
                        
                        <div>
                            <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icofav.png"> <span id="numlikes"><?php echo($D->p_numlikes)?></span></span>
			                <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icocomment.png"> <span id="numcom"><?php echo($D->p_numcomments)?></span></span>
            			    <span class="mrg10L"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoview.png"> <?php echo($D->p_numviews)?></span>
                        
                        </div>
                        
                        
                        <script>var iu=<?php echo $D->idUser?>;</script>
                        <?php if ($D->liketoUser==1) { ?>
                        
                        <div class="mrg10T">
                        	<div class="liked linkblue"><span class="hand"><a onclick="liked(<?php echo $D->idItem?>,0)"><?php echo $this->lang('profile_items_like_unlike')?></a></span></div>
                        </div>
                        
                        <?php } else { ?>

                        <div class="mrg10T">
                        	
                            <?php if ($D->is_logged == 1) { ?>
								
                                <div class="liked linkblue"><span class="hand"><a onclick="liked(<?php echo $D->idItem?>,1)"><?php echo $this->lang('profile_items_like_like')?></a></span></div>
                                
                            <?php } else { ?>
								
                                <div class="liked linkblue"><span class="hand"><a onclick="nologged()"><?php echo $this->lang('profile_items_like_like')?></a></span></div>
                                <div id="nologged" class="only-redbox hide"><?php echo $this->lang('profile_items_txtyoumustbe');?> <span class="linkblue"><a href="<?php echo $C->SITE_URL?>login"><?php echo $this->lang('profile_items_txtsigned');?></a></span>.</div>
                                
                                
                                <script>
								function nologged()
								{
									$('.liked').slideUp('slow', function(){
										$('#nologged').slideDown('slow');
									});	
								}
								</script>
                            
                            <?php } ?>
                        </div>
                        
                        <?php } ?>
                        
                        <hr class="mrg10T mrg10B">
                        
                        <?php if ($D->censoredbyUser==1) { ?>
                        
                        <div class="mrg10T">
                        	<div class="censored linkblue"><span class="hand"><a onclick="censored(<?php echo $D->idItem?>,0)"><?php echo $this->lang('profile_censor_uncensor')?></a></span></div>
                        </div>
                        
                        <?php } else { ?>

                        <div class="mrg10T">
                        	
                            <?php if ($D->is_logged == 1) { ?>
								
                                <div class="censored linkblue"><span class="hand"><a onclick="censored(<?php echo $D->idItem?>,1)"><?php echo $this->lang('profile_censor_censor')?></a></span></div>
                                
                            <?php } else { ?>
								
                                <div class="censored linkblue"><span class="hand"><a onclick="nologged2()"><?php echo $this->lang('profile_censor_censor')?></a></span></div>
                                <div id="nologged2" class="only-redbox hide"><?php echo $this->lang('profile_items_txtyoumustbe');?> <span class="linkblue"><a href="<?php echo $C->SITE_URL?>"><?php echo $this->lang('profile_items_txtsigned');?></a></span>.</div>
                                
                                
                                <script>
								function nologged2()
								{
									$('.censored').slideUp('slow', function(){
										$('#nologged2').slideDown('slow');
									});	
								}
								</script>
                            
                            <?php } ?>
                        </div>
                        
                        <?php } ?>
                        
                        
                        <hr class="mrg10T mrg10B">
                        

					</div>

                    
                    <div id="precomment" class="centered">
                    	<div><span id="bprecom" class="byellow rounded"><?php echo $this->lang('profile_items_comment_txtwrite')?></span></div>
                    </div>
                    
                    <?php if ($D->is_logged == 1) { ?>
                    
                    <div id="sectioncomment">
                    	<div class="avatar"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($this->user->info->avatar)?$C->AVATAR_DEFAULT:$this->user->info->avatar)?>" class="rounded"></div>
                        <div class="areainput">
                        	<form name="form1" method="post" action="">
                        	<div class="mrg10B"><textarea name="comment" id="comment" class="boxinput" placeholder="<?php echo $this->lang('profile_items_comment_txtleave')?>"></textarea></div>
                            <div id="msgerror" class="redbox"></div>
                            <div><input type="submit" name="bsave" id="bsave" value="<?php echo $this->lang('profile_items_comment_txtbsave')?>" class="bblue hand">
                              <input name="ip" type="hidden" id="ip" value="<?php echo $D->idItem?>" />
                              <input name="iu" type="hidden" id="iu" value="<?php echo $D->idUser?>" />
                              
                            </div>
                            </form>
                        </div>
                        
                    </div>
                    
                    <?php } else { ?>
                    
                    <div id="sectioncomment">
                    	<div class="only-redbox"><?php echo $this->lang('profile_items_txtyoumustbe');?> <span class="linkblue"><a href="<?php echo $C->SITE_URL?>login"><?php echo $this->lang('profile_items_txtsigned');?></a></span>.</div>
                    </div>
                    
                    <?php } ?>
                    
                    
                    <div class="titlecomments mrg20T"><?php echo $this->lang('profile_items_comment_title')?></div>
                    
                    <div id="listcomments">
                    
                        <div id="newcomment"></div>
                        
                        <?php if (empty($D->htmlComments)) { ?>
                        
                        <div id="nocomments" class="withoutcomments mrg10T"><?php echo $this->lang('profile_items_comment_txtwithout')?></div>
                        
                        <?php } else {?>
                        
                        <div><?php echo $D->htmlComments?></div>
                        
                        <?php } ?>
                        
                    </div>
                
                </div>
            
            </div>

            
        <?php } ?>
        </div>
        
        <div id="divseparator" class="sh"></div>
        
        <div id="column3"><?php $this->load_template('_accessories.php'); ?></div>
        
        <div class="sh"></div>
    
    </div>
            
</div>
<script>
	transformTextarea();

	$('#bprecom').one('click',function(){
		$('#precomment').slideUp('slow', function(){$('#sectioncomment').slideDown('slow');});
	});	
	
	var norequest = '<?php echo $this->lang('global_txt_no_request');?>';
	var msgnocomment = '<?php echo $this->lang('profile_items_txtnocomment');?>';
	$('#bsave').click(function(){
		saveComment('#msgerror', '#bsave');
		return false;
	});

</script>
<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>