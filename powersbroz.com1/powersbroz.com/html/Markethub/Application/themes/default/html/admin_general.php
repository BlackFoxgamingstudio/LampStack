<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_admin.js"></script>
<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1-admin"><?php $this->load_template('_verticalmenu-admin.php'); ?></div>
        
        <div id="column2-admin">
		
            <div id="dashboard-admin2">

				<div class="title"><?php echo $this->lang('admin_general_title'); ?></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_general_subtitle1');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                        <form id="form1" name="form1" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_title')?></div>
						<div><input name="titlesite" type="text" id="titlesite" value="<?php echo $D->SITE_TITLE?>" class="boxinput withbox" /></div>
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_site_description')?></div>
						<div><input name="descsite" type="text" id="descsite" value="<?php echo $D->SITE_DESCRIPTION?>" class="boxinput withbox" /></div>
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_site_keywords')?></div>
						<div><input name="keywsite" type="text" id="keywsite" value="<?php echo $D->SITE_KEYWORDS?>" class="boxinput withbox" /></div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_protected')?></div>
                        <div class="mrg5T">
                        <select name="protected" id="protected" class="combobox">
                        	<option value="1" <?php echo(1==$D->PROTECT_OUTSIDE_PAGES?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_protected_opc01')?></option>
                            <option value="0" <?php echo(0==$D->PROTECT_OUTSIDE_PAGES?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_protected_opc02')?></option>
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_languages')?></div>
                        <div class="mrg5T">
                        <select name="language" id="language" class="combobox">
                        	<option value="1" <?php echo(1==$D->SHOW_MENU_LANGUAJE?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_languages_opc01')?></option>
                            <option value="0" <?php echo(0==$D->SHOW_MENU_LANGUAJE?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_languages_opc02')?></option>
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_txt_pages')?></div>
                        <div class="mrg5T">
                        <select name="spages" id="spages" class="combobox">
                        	<option value="1" <?php echo(1==$D->SHOW_MENU_PAGES?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_pages_opc01')?></option>
                            <option value="0" <?php echo(0==$D->SHOW_MENU_PAGES?'selected="selected"':'')?>><?php echo $this->lang('admin_general_txt_pages_opc02')?></option>
                        </select>
                        </div>
                                            
                        <div id="msgerror1" class="redbox"></div>
                        <div id="msgok1" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave1" id="bsave1" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
                        </div>
                        
                        </form>
                        
                    </div>
				</div>
                
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_general_subtitle2');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                        <form id="form2" name="form2" method="post" action="">                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_ntf_events')?></div>
                        <div class="mrg5T">
                        <select name="notievents" id="notievents" class="combobox">
                        
                        	<?php for($i=3; $i<=12; $i=$i+3) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_NOTIFICATIONS_ALERT?'selected="selected"':'')?>><?php echo $i.' '.$this->lang('admin_general_ntf_txt')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_ntf_interval_events')?></div>
                        <div class="mrg5T">
                        <select name="notieventsinterval" id="notieventsinterval" class="combobox">
                        
                        	<option value="10000" <?php echo(10000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>10 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="20000" <?php echo(20000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>20 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="30000" <?php echo(30000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>30 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="60000" <?php echo(60000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>1 <?php echo $this->lang('admin_general_ntf_txt_minute')?></option>
                            <option value="120000" <?php echo(120000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>2 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            <option value="300000" <?php echo(300000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>5 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            <option value="600000" <?php echo(600000==$D->INTERVAL_NOTIFICATIONS_EVENTS?'selected="selected"':'')?>>10 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_ntf_msgs')?></div>
                        <div class="mrg5T">
                        <select name="notimsg" id="notimsg" class="combobox">
                        
                        	<?php for($i=3; $i<=12; $i=$i+3) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_NOTIFICATIONSMSG_ALERT?'selected="selected"':'')?>><?php echo $i.' '.$this->lang('admin_general_ntf_txt')?></option>
                            <?php } ?>

                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_ntf_interval_msgs')?></div>
                        <div class="mrg5T">
                        <select name="notieventsintervalmsg" id="notieventsintervalmsg" class="combobox">
                        
                        	<option value="10000" <?php echo(10000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>10 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="20000" <?php echo(20000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>20 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="30000" <?php echo(30000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>30 <?php echo $this->lang('admin_general_ntf_txt_seconds')?></option>
                            <option value="60000" <?php echo(60000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>1 <?php echo $this->lang('admin_general_ntf_txt_minute')?></option>
                            <option value="120000" <?php echo(120000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>2 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            <option value="300000" <?php echo(300000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>5 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            <option value="600000" <?php echo(600000==$D->INTERVAL_NOTIFICATIONS_MSGS?'selected="selected"':'')?>>10 <?php echo $this->lang('admin_general_ntf_txt_minutes')?></option>
                            
                        </select>
                        </div>
                                            
                        <div id="msgerror2" class="redbox"></div>
                        <div id="msgok2" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave2" id="bsave2" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
                        </div>
                        
                        </form>
                        
                    </div>
				</div>
                
                
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_general_subtitle3');?></div>
                    <hr />
                    
                    <div id="form03">
                    
                        <form id="form3" name="form3" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_chat_nummsgchat')?></div>
                        <div class="mrg5T">
                        <select name="numchatstart" id="numchatstart" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->CHAT_NUM_MSG_START?'selected="selected"':'')?>><?php echo $i?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_chat_refresh')?></div>
                        <div class="mrg5T">
                        <select name="intervalmsgchat" id="intervalmsgchat" class="combobox">
                        
                        	<?php for($i=1; $i<=10; $i++) { ?>
                        	<option value="<?php echo $i*1000?>" <?php echo(($i*1000)==$D->CHAT_INTERVAL_REFRESH?'selected="selected"':'')?>><?php echo $i.' '.($i==1?$this->lang('admin_general_chat_txt_second'):$this->lang('admin_general_chat_txt_seconds'))?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_chat_emoticons')?></div>
                        <div class="mrg5T">
                        <select name="chatemoticons" id="chatemoticons" class="combobox">
                        	<option value="1" <?php echo(1==$D->CHAT_WITH_EMOTICONS?'selected="selected"':'')?>><?php echo $this->lang('admin_general_chat_emoticons_opc01')?></option>
                            <option value="0" <?php echo(0==$D->CHAT_WITH_EMOTICONS?'selected="selected"':'')?>><?php echo $this->lang('admin_general_chat_emoticons_opc02')?></option>
                        </select>
                        </div>
                                            
                        <div id="msgerror3" class="redbox"></div>
                        <div id="msgok3" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave3" id="bsave3" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
                        </div>
                        
                        </form>
                        
                    </div>
				</div>
                
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_general_subtitle4');?></div>
                    <hr />
                    
                    <div id="form04">
                    
                        <form id="form4" name="form4" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_activities')?></div>
                        <div class="mrg5T">
                        <select name="numactivities" id="numactivities" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_ACTIVITIES_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_followers')?></div>
                        <div class="mrg5T">
                        <select name="numfollowers" id="numfollowers" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_FOLLOWERS_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_following')?></div>
                        <div class="mrg5T">
                        <select name="numfollowing" id="numfollowing" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_FOLLOWING_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_favorites')?></div>
                        <div class="mrg5T">
                        <select name="numfavorites" id="numfavorites" class="combobox">
                        
                        	<?php for($i=12; $i<=44; $i=$i+4) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_FAVORITES_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_notifications')?></div>
                        <div class="mrg5T">
                        <select name="numnotifications" id="numnotifications" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_NOTIFICATIONS_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_messages')?></div>
                        <div class="mrg5T">
                        <select name="numitemmsg" id="numitemmsg" class="combobox">
                        
                        	<?php for($i=10; $i<=40; $i=$i+5) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_USERCHAT_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_search')?></div>
                        <div class="mrg5T">
                        <select name="numsearch" id="numsearch" class="combobox">
                        
                        	<?php for($i=12; $i<=36; $i=$i+6) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_SEARCH_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        

                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_directory_people')?></div>
                        <div class="mrg5T">
                        <select name="numdirpeople" id="numdirpeople" class="combobox">
                        
                        	<?php for($i=8; $i<=40; $i=$i+4) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_USERS_DIRECTORY_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>

                      
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_directory_media')?></div>
                        <div class="mrg5T">
                        <select name="numdirmedia" id="numdirmedia" class="combobox">
                        
                        	<?php for($i=12; $i<=36; $i=$i+6) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_PHOTOS_DIRECTORY_PAGE?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_instant_search_top')?></div>
                        <div class="mrg5T">
                        <select name="numinstantsearch" id="numinstantsearch" class="combobox">
                        
                        	<?php for($i=5; $i<=10; $i++) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->NUM_RESULT_SEARCH_TOP?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>
                        
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_general_items_recents_home')?></div>
                        <div class="mrg5T">
                        <select name="numrecenthome" id="numrecenthome" class="combobox">
                        
                        	<?php for($i=6; $i<=36; $i=$i+6) { ?>
                        	<option value="<?php echo $i?>" <?php echo($i==$D->ITEMS_RECENTS_HOME?'selected="selected"':'')?>><?php echo $i.' / '.$this->lang('admin_general_items_txtpage')?></option>
                            <?php } ?>
                            
                        </select>
                        </div>

                                            
                        <div id="msgerror4" class="redbox"></div>
                        <div id="msgok4" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave4" id="bsave4" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
                        </div>
                        
                        </form>
                        
                    </div>
				</div>
                

            </div>
        
        
        </div>
        
        <div class="sh"></div>
    
    </div>
            
</div>

<script>
	var admin_norequest = '<?php echo $this->lang('admin_no_request');?>';
	
	var txt_error1 = '<?php echo $this->lang('admin_general_txt_error1');?>';
	var txt_error2 = '<?php echo $this->lang('admin_general_txt_error2');?>';
	var txt_error3 = '<?php echo $this->lang('admin_general_txt_error3');?>';
	$('#bsave1').click(function(){
		updateGeneral('#msgerror1','#msgok1','#bsave1');
		return false;
	});
	
	$('#bsave2').click(function(){
		updateUserNotficactions('#msgerror2','#msgok2','#bsave2');
		return false;
	}); 
	
	$('#bsave3').click(function(){
		updateUserChats('#msgerror3','#msgok3','#bsave3');
		return false;
	});
	
	$('#bsave4').click(function(){
		updateShowItems('#msgerror4','#msgok4','#bsave4');
		return false;
	}); 
</script>  

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>