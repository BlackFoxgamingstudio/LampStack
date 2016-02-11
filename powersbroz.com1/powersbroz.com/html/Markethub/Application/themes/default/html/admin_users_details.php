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

				<div class="title"><?php echo $this->lang('admin_manageuser_title'); ?></div>
                
                <div id="linkback" class="mrg10T linkblue">&laquo; <a href="<?php echo $C->SITE_URL?>admin/users/p:<?php echo $D->npage?>"><?php echo $this->lang('admin_manageuser_linkback')?></a></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_manageuser_subtitle2');?></div>
                    <hr />
                    
                    
                    
                    <div id="userdetails" class="mrg10T">
                    
                    	<div class="mrg10T grey1"><span class="grey1"><?php echo $this->lang('admin_manageuser_avatar')?>:</span> <span><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min1/'.(empty($D->avatar)?'default.jpg':$D->avatar); ?>" class="rounded"></span>
                        </div>
                        
                        <div class="mrg10T">
                        	<span class="grey1"><?php echo $this->lang('admin_manageuser_username')?>:</span> <span class="txtsize00"><?php echo $D->username?></span>
                        </div>
                        
                        <div class="mrg10T">
                        	<span class="grey1"><?php echo $this->lang('admin_manageuser_firstname')?>:</span> <span class="txtsize00"><?php echo $D->firstname?></span>
                        </div>
                        
                        <div class="mrg10T">
                        	<span class="grey1"><?php echo $this->lang('admin_manageuser_lastname')?>:</span> <span class="txtsize00"><?php echo $D->lastname?></span>
                        </div>


                    

                        
                    </div>                    
                    
				</div>
                
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_manageuser_txtvalidated');?></div>
                    <hr />
                    
                    <div id="form04">
                    
                      <form id="formvalidated" name="formvalidated" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_manageuser_txtvalidated_msg')?></div>
                        <div class="mrg5T">
                        <select name="mvalidated" id="mvalidated" class="combobox">
                        	<option value="1" <?php echo(1==$D->validated?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_txtisvalidated')?></option>
                            <option value="0" <?php echo(0==$D->validated?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_txtisnotvalidated')?></option>
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
                
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_manageuser_subtitle3');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                      <form id="formstatus" name="formstatus" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_manageuser_txtuserstatus')?></div>
                        <div class="mrg5T">
                        <select name="mstatus" id="mstatus" class="combobox">
                        	<option value="1" <?php echo(1==$D->active?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_active')?></option>
                            <option value="0" <?php echo(0==$D->active?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_inactive')?></option>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_manageuser_subtitle4');?></div>
                    <hr />
                    
                    <div id="form02">
                    
                      <form id="formlevel" name="formlevel" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_manageuser_txtleveluser')?></div>
                        <div class="mrg5T">
                        <select name="level" id="level" class="combobox">
                        	<option value="1" <?php echo(1==$D->isadministrador?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_isadministrator')?></option>
                            <option value="0" <?php echo(0==$D->isadministrador?'selected="selected"':'')?>><?php echo $this->lang('admin_manageuser_notadministrator')?></option>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_manageuser_subtitle5');?></div>
                    <hr />
                    
                    <div id="form03">
                    
                      <form id="formdelete" name="formdelete" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_manageuser_txtdelete')?></div>
                                            
                        <div id="msgerror3" class="redbox"></div>
                        <div id="msgok3" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave3" id="bsave3" value="<?php echo $this->lang('admin_manageuser_bdelete')?>" class="bred hand"/>
                        
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
	var uidd = <?php echo $D->iduser?>;
	
	$('#bsave1').click(function(){
		updateStatus('#msgerror1','#msgok1','#bsave1');
		return false;
	});
	
	$('#bsave2').click(function(){
		updateLevel('#msgerror2','#msgok2','#bsave2');
		return false;
	});
	
	var msgalert = '<?php echo $this->lang('admin_manageuser_txtalert');?>';
	var ppage = <?php echo $D->npage?>;
	$('#bsave3').click(function(){
		deleteUser('#msgerror3','#msgok3','#bsave3');
		return false;
	});
	
	$('#bsave4').click(function(){
		updateValidated('#msgerror4','#msgok4','#bsave4');
		return false;
	});

</script>      

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>