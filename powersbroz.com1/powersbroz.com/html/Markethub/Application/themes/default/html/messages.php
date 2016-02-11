<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_profile_chat.js"></script>
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
            
            	<div class="title mrg20T"><?php echo $this->lang('profile_messages_title'); ?></div>
            
                <div class="boxchat">
                  <div class="chatheader">
                        <div class="ico"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icochat.png"></div>
                        <div class="info">
                            <div class="iuser"><?php echo $D->nameUser?></div>
                        </div>
                        <div class="sh"></div>	
                    </div>

					<?php if ($D->is_my_profile) { ?>
                    <div class="chatbodyno">
                      <div class="spacemsgno"><?php echo $this->lang('profile_messages_notavailable');?></div>
                    </div>
                    <?php } elseif($D->is_logged==0) {?>
                    <div class="chatbodyno">
                      <div class="spacemsgno"><?php echo $this->lang('profile_messages_notlogin');?></div>
                    </div>
                    <?php } else { ?>
                    

                    
                    <div class="chatbody">

						<?php if ($D->totalmsgchat>$C->CHAT_NUM_MSG_START) { ?>
                        <div class="loadmorechat">
                            <span id="linkmore" class="hand onlyblue"><?php echo $this->lang('profile_messages_viewmore')?></span>
                            <span id="morepreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif" /></span>
                            <input name="numitems" type="hidden" id="numitems" value="<?php echo $D->nummsgchat?>" />
                        </div>
                        <script type="text/javascript">
                        var uid = <?php echo $D->u->iduser ?>;
                        var itemperpage = <?php echo $C->CHAT_NUM_MSG_START ?>;
                        $('#linkmore').click(function(){
                            reload_msgchat()
                            return false;
                        });
                        </script>
                        <?php } ?>
                    
                    	<div class="txtchats"><?php echo $D->htmlChat; ?></div>
                        
                    </div>
                    
                    <div class="entertxt">
                        <input type="text" name="inputchat" id="inputchat" placeholder="<?php echo $this->lang('profile_messages_placeholderchat');?>"  />
                        <input name="uid" type="hidden" id="uid" value="<?php echo $D->u->iduser ?>" />
                    </div>
                    
                    <script type="text/javascript" src="<?php $C->SITE_URL ?>themes/default/js/js_messageschat.js"></script>
                    
					<script type="text/javascript">
					$(".chatbody").scrollTop($(".chatbody")[0].scrollHeight);
					var msg_norequest = '<?php echo $this->lang('global_txt_no_request');?>';
					var intervalrefreshchat = <?php echo $C->CHAT_INTERVAL_REFRESH?>;
                    checkNewMsgChat();

                    </script>
                    
                    
                    
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