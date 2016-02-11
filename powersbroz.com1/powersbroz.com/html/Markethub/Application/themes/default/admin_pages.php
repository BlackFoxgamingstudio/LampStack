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

				<div class="title"><?php echo $this->lang('admin_pages_title'); ?></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_pages_subtitle1');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                        <form id="form1" name="form1" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_pages_textenter')?></div>
                        <div>
                        <textarea name="txtabout" rows="8" class="boxinput withbox" id="txtabout"><?php echo stripslashes($D->about)?></textarea>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_pages_subtitle2');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                        <form id="form2" name="form2" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_pages_textenter')?></div>
                        <div>
                        <textarea name="txtprivacy" rows="8" class="boxinput withbox" id="txtprivacy"><?php echo stripslashes($D->privacy)?></textarea>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_pages_subtitle3');?></div>
                    <hr />
                    
                    <div id="form03">
                    
                        <form id="form3" name="form3" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_pages_textenter')?></div>
                        <div>
                        <textarea name="txttermsofuse" rows="8" class="boxinput withbox" id="txttermsofuse"><?php echo stripslashes($D->termsofuse)?></textarea>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_pages_subtitle4');?></div>
                    <hr />
                    
                    <div id="form04">
                    
                        <form id="form4" name="form4" method="post" action="">
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_pages_textenter')?></div>
                        <div>
                        <textarea name="txtdisclaimer" rows="8" class="boxinput withbox" id="txtdisclaimer"><?php echo stripslashes($D->disclaimer)?></textarea>
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
                
                    <div class="subtitle"><?php echo $this->lang('admin_pages_subtitle5');?></div>
                    <hr />
                    
                    <div id="form05">
                    
                        <form id="form5" name="form5" method="post" action="">

                        <div class="mrg10T grey1"><?php echo $this->lang('admin_pages_textenter')?></div>
                        <div>
                        <textarea name="txtcontact" rows="8" class="boxinput withbox" id="txtcontact"><?php echo stripslashes($D->contact)?></textarea>
                        </div>
                                            
                        <div id="msgerror5" class="redbox"></div>
                        <div id="msgok5" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave5" id="bsave5" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
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
	var admin_txt_error = '<?php echo $this->lang('admin_pages_txterror');?>';
	
	$('#bsave1').click(function(){
		updatePages('#msgerror1','#msgok1','#bsave1',1);
		return false;
	});
	
	$('#bsave2').click(function(){
		updatePages('#msgerror2','#msgok2','#bsave2',2);
		return false;
	});
	
	$('#bsave3').click(function(){
		updatePages('#msgerror3','#msgok3','#bsave3',3);
		return false;
	});
	
	$('#bsave4').click(function(){
		updatePages('#msgerror4','#msgok4','#bsave4',4);
		return false;
	});
	
	$('#bsave5').click(function(){
		updatePages('#msgerror5','#msgok5','#bsave5',5);
		return false;
	});

</script>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>