<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_basic.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_d_myinformation.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_locations.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_uploadimage.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/md5.js"></script>
<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1"><?php $this->load_template('_verticalmenu-dashboard.php'); ?></div>
        
        <div id="column2">
		
            <div id="dashboard2">
                <div class="title"><?php echo $this->lang('dashboard_mi_title')?></div>

                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_pi_title')?></div>
                    <hr />
                    
                    <div id="form01">
                    	<form id="form1" name="form1" method="post" action="">
                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_pi_form_fname')?>:</div>
                    	<div><input name="firstname" type="text" id="firstname" value="<?php echo $D->me->firstname?>" class="boxinput withbox"/></div>
                        
						<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_pi_form_lname')?>:</div>
                    	<div><input name="lastname" type="text" id="lastname" value="<?php echo $D->me->lastname?>" class="boxinput withbox" /></div>
                        
						<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_pi_form_gender')?>:</div>
                    	<div>
							<select name="gender" id="gender" class="combobox">
                              <option value="0" <?php echo($D->me->gender==0?'selected="selected"':'');?>><?php echo $this->lang('dashboard_mi_pi_form_gender_txtsex')?>:</option>
                              <option value="1" <?php echo($D->me->gender==1?'selected="selected"':'');?>><?php echo $this->lang('dashboard_mi_pi_form_gender_male')?></option>
                              <option value="2" <?php echo($D->me->gender==2?'selected="selected"':'');?>><?php echo $this->lang('dashboard_mi_pi_form_gender_female')?></option>
                            </select>
                        </div>
                        
						<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_pi_form_birthay')?>:</div>
                    	<div>
<select name="day" id="day" class="combobox">
      <option value="0" <?php echo $D->me->born==0?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_day')?>:</option>
      <?php for ($x=1; $x<=31; $x++) { ?>
      <option value="<?php echo $x?>" <?php echo (date('j',$D->me->born)==$x && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $x?></option>
      <?php } ?>          
    </select>
      <select name="month" id="month" class="combobox">
        <option value="0" <?php echo ($D->me->born==0 ? 'selected="selected"':'')?>><?php echo $this->lang('dashboard_mi_pi_form_month')?>:</option>
        <option value="1" <?php echo (date('n',$D->me->born)==1 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_jan')?></option>
        <option value="2" <?php echo (date('n',$D->me->born)==2 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_feb')?></option>
        <option value="3" <?php echo (date('n',$D->me->born)==3 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_mar')?></option>
        <option value="4" <?php echo (date('n',$D->me->born)==4 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_apr')?></option>
        <option value="5" <?php echo (date('n',$D->me->born)==5 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_may')?></option>
        <option value="6" <?php echo (date('n',$D->me->born)==6 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_jun')?></option>
        <option value="7" <?php echo (date('n',$D->me->born)==7 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_jul')?></option>
        <option value="8" <?php echo (date('n',$D->me->born)==8 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_aug')?></option>
        <option value="9" <?php echo (date('n',$D->me->born)==9 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_sep')?></option>
        <option value="10" <?php echo (date('n',$D->me->born)==10 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_oct')?></option>
        <option value="11" <?php echo (date('n',$D->me->born)==11 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_nov')?></option>
        <option value="12" <?php echo (date('n',$D->me->born)==12 && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_dic')?></option>
      </select>
      <select name="year" id="year" class="combobox">
      <option value="0" <?php echo $D->me->born==0?'selected="selected"':'' ?>><?php echo $this->lang('dashboard_mi_pi_form_year')?>:</option>
          <?php for ($x=date("Y"); $x>=(date("Y")-106); $x--) { ?>
          <option value="<?php echo $x?>" <?php echo (date('Y',$D->me->born)==$x && $D->me->born!=0)?'selected="selected"':'' ?>><?php echo $x?></option>
          <?php } ?>                
      </select>
                        
                        </div>
                        
                        <div id="msgerror1" class="redbox"></div>
                        <div id="msgok1" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave1" id="bsave1" value="<?php echo $this->lang('dashboard_mi_pi_form_bsubmit')?>" class="bblue hand"/>
                        </div>
                        
                   	  
                      </form>
                    </div>
                </div>

				
                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_am_title');?></div>
                    <hr />
                    
                     <div id="form02">
                    	<form id="form2" name="form2" method="post" action="">
                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_am_form_msgcaption')?></div>
                    	<div>
                        <textarea name="aboutme" rows="6" class="boxinput withbox" id="aboutme"><?php echo stripslashes($D->me->aboutme);?></textarea>
                        </div>
                                                
                        <div id="msgerror2" class="redbox"></div>
                        <div id="msgok2" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave2" id="bsave2" value="<?php echo $this->lang('dashboard_mi_am_form_bsubmit')?>" class="bblue hand"/>
                        </div>
                   	  
                      </form>
                    </div>

                </div>
                
               
                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_mav_title')?></div>
                     <hr />
                     
                     <div id="form03">
                         <form id="form3" name="form3" method="post" action="">
                         
                         <div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_mav_form_msgcaption')?></div>
                         <div class="mrg10T"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.(empty($D->me->avatar)?$C->AVATAR_DEFAULT:$D->me->avatar)?>" id="prwimg" class="previewavatardash"></div>
                         <div class="mrg10T mrg10B"><span id="linkUpImage" class="onlyblue bold hand"><?php echo $this->lang('dashboard_mi_mav_form_msg1')?></span></div>
                         <div>
                            <div id="areapreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif"/></div>
                            <input name="loadedimage" type="hidden" id="loadedimage" value="<?php echo $D->me->avatar?>" />
                            <input name="didchanges" type="hidden" id="didchanges" value="0" />
                         </div>

                        <div id="msgerror3" class="redbox"></div>
                        <div id="msgok3" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave3" id="bsave3" value="<?php echo $this->lang('dashboard_mi_mav_form_bsubmit')?>" class="bblue hand"/>
                        </div>

                         </form>
                         
                    </div>
                     
                </div>
                
                
                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_ia_title');?></div>
                     <hr />
                     <div id="form04">
                     <form id="form4" name="form4" method="post" action="">
                    	<div class="mrg10T"><span class="grey1"><?php echo $this->lang('dashboard_mi_ia_form_email')?>:</span> <span class="bold mrg10L"><?php echo $D->me->email?></span></div>
                    	<div class="mrg10T"><span class="grey1"><?php echo $this->lang('dashboard_mi_ia_form_username')?>:</span> <span class="bold mrg10L"><?php echo $D->me->username?></span></div>
                        
                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_ia_form_currentpass')?>:</div>
                    	<div><input name="currentpass" type="password" id="currentpass" class="boxinput withbox"/></div>

                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_ia_form_newpass')?>:</div>
                    	<div><input name="newpass" type="password" id="newpass" class="boxinput withbox"/></div>

                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_ia_form_newpass2')?>:</div>
                    	<div><input name="newpass2" type="password" id="newpass2" class="boxinput withbox"/></div>

                        <div id="msgerror4" class="redbox"></div>
                        <div id="msgok4" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave4" id="bsave4" value="<?php echo $this->lang('dashboard_mi_ia_form_bsubmit')?>" class="bblue hand"/>
                        </div>

                     </form>
                     </div>
                </div>

                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_lo_title')?></div>
                     <hr />
                     
                     <div id="form05">
                     <form id="form5" name="form5" method="post" action="">
                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_lo_form_txtcountry')?></div>
                    	<div><select name="country" id="country" class="boxinput withbox" onchange="loadRegions(this.value,0, '<?php echo $this->lang('dashboard_mi_lo_form_msgcomboregion')?>','#region');"></select></div>

                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_lo_form_txtregion')?></div>
                    	<div><select name="region" id="region" class="boxinput withbox"></select></div>

                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_lo_form_txtcity')?></div>
                    	<div><input name="city" type="text" class="boxinput withbox" id="city" value="<?php echo $D->me->city?>" maxlength="50"></div>
                                                
                        <div id="msgerror5" class="redbox"></div>
                        <div id="msgok5" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave5" id="bsave5" value="<?php echo $this->lang('dashboard_mi_lo_form_bsubmit')?>" class="bblue hand"/>
                        </div>                     
                     </form>
                     </div>
                </div>

                <div class="editarea">
                	<div class="subtitle"><?php echo $this->lang('dashboard_mi_pr_title')?></div>
                     <hr />
                     
                     <div id="form06">
                     <form id="form6" name="form6" method="post" action="">
                    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_mi_pr_form_txtprofile')?></div>
                    	<div><select name="privacy" id="privacy" class="boxinput withbox">
                    	  <option value="0" <?php echo($D->me->{'privacy'}==0?'selected="selected"':'')?>><?php echo $this->lang('dashboard_mi_pr_form_txtpublic')?></option>
                    	  <option value="1" <?php echo($D->me->{'privacy'}==1?'selected="selected"':'')?>><?php echo $this->lang('dashboard_mi_pr_form_txtprivate')?></option>
                    	  <option value="2" <?php echo($D->me->{'privacy'}==2?'selected="selected"':'')?>><?php echo $this->lang('dashboard_mi_pr_form_txtonlyfollowers')?></option>
                    	</select></div>

                        <div id="msgerror6" class="redbox"></div>
                        <div id="msgok6" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                            <input type="submit" name="bsave6" id="bsave6" value="<?php echo $this->lang('dashboard_mi_pr_form_bsubmit')?>" class="bblue hand"/>
                        </div>                     
                     </form>
                     </div>
                </div>
                
                
            </div>
        
        
        </div>
        
        <div id="divseparator" class="sh"></div>
        
        <div id="column3"><?php $this->load_template('_accessories-dashboard.php'); ?></div>
        
        <div class="sh"></div>
    
    </div>
            
</div>

<script>
	var mi_norequest = '<?php echo $this->lang('dashboard_no_request')?>';
	
	var mi_pi_msg1 = '<?php echo $this->lang('dashboard_mi_pi_form_msg1')?>';
	var mi_pi_msg2 = '<?php echo $this->lang('dashboard_mi_pi_form_msg2')?>';
	var mi_pi_msg3 = '<?php echo $this->lang('dashboard_mi_pi_form_msg3')?>';
	var mi_pi_msg4 = '<?php echo $this->lang('dashboard_mi_pi_form_msg4')?>';
	var mi_pi_msg5 = '<?php echo $this->lang('dashboard_mi_pi_form_msg5')?>';
	$('#bsave1').click(function(){
		updatePersonalInfo('#msgerror1','#msgok1','#bsave1');
		return false;
	}); 
	

	var mi_am_msg1 = '<?php echo $this->lang('dashboard_mi_am_form_msg1')?>';
	$('#bsave2').click(function(){
		updateAboutMe('#msgerror2','#msgok2','#bsave2');
		return false;
	}); 
	
	var mi_mav_msg1 = '<?php echo $this->lang('dashboard_mi_mav_form_msg3')?>';
	var mi_mav_msg2 = '<?php echo $this->lang('dashboard_mi_mav_form_msg2')?>';
	var mi_mav_msg3 = '<?php echo $this->lang('dashboard_mi_mav_form_msg5')?>';
	var folderphotos = '<?php echo $C->FOLDER_PHOTOS?>';
	$('#linkUpImage').uploadImage(1,'<?php echo $C->FOLDER_TMP?>','_av_',mi_mav_msg1,mi_mav_msg2);
	$('#bsave3').click(function(){
		updateMyAvatar('#msgerror3','#msgok3','#bsave3');
		return false;
	});
	
	var mi_ia_msg1 = '<?php echo $this->lang('dashboard_mi_ia_form_msg1')?>';
	var mi_ia_msg2 = '<?php echo $this->lang('dashboard_mi_ia_form_msg2')?>';
	var mi_ia_msg3 = '<?php echo $this->lang('dashboard_mi_ia_form_msg3')?>';
	var mi_ia_msg4 = '<?php echo $this->lang('dashboard_mi_ia_form_msg4')?>';
	$('#bsave4').click(function(){
		updateInfoAccess('#msgerror4','#msgok4','#bsave4');
		return false;
	}); 
	
	codecountry = '<?php echo $D->me->codecountry?>';
    idregion = <?php echo $D->me->idregion?>;
	var msgccountry='<?php echo $this->lang('dashboard_mi_lo_form_msgcombocountry')?>';
	var msgcregion='<?php echo $this->lang('dashboard_mi_lo_form_msgcomboregion')?>';
	loadCountries(codecountry, msgccountry, msgcregion, '#country');
	loadRegions(codecountry, idregion, msgcregion, '#region');
	var mi_lo_msg1='<?php echo $this->lang('dashboard_mi_lo_form_msg1')?>';
	var mi_lo_msg2='<?php echo $this->lang('dashboard_mi_lo_form_msg2')?>';
	var mi_lo_msg3='<?php echo $this->lang('dashboard_mi_lo_form_msg3')?>';
	$('#bsave5').click(function(){
		updateLocation('#msgerror5','#msgok5','#bsave5');
		return false;
	});
	

	$('#bsave6').click(function(){
		updatePrivacy('#msgerror6','#msgok6','#bsave6');
		return false;
	});
</script>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>