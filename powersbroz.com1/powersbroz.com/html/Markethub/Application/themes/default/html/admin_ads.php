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

				<div class="title"><?php echo $this->lang('admin_ads_title'); ?></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_ads_subtitle1');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                        <form id="formads1" name="formads1" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_ads_profile1')?></div>
                        <div>
                        <textarea name="adsp1" rows="5" class="boxinput withbox" id="adsp1"><?php echo stripslashes($D->adsp1)?></textarea>
                        </div>
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_ads_profile2')?></div>
                        <div>
                        <textarea name="adsp2" rows="5" class="boxinput withbox" id="adsp2"><?php echo stripslashes($D->adsp2)?></textarea>
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
                    
                    <div class="subtitle"><?php echo $this->lang('admin_ads_subtitle2');?></div>
                    <hr />
                    
                    <div id="form02">
                    
                        <form id="formads2" name="formads2" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_ads_dashboard1')?></div>
                        <div>
                        <textarea name="adsd1" rows="6" class="boxinput withbox" id="adsd1"><?php echo stripslashes($D->adsd1)?></textarea>
                        </div>
                        
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_ads_dashboard2')?></div>
                        <div>
                        <textarea name="adsd2" rows="6" class="boxinput withbox" id="adsd2"><?php echo stripslashes($D->adsd2)?></textarea>
                        </div>
                                            
                        <div id="msgerror2" class="redbox"></div>
                        <div id="msgok2" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave2" id="bsave2" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
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
	
	$('#bsave1').click(function(){
		updateAdsP('#msgerror1','#msgok1','#bsave1');
		return false;
	});
	
	$('#bsave2').click(function(){
		updateAdsD('#msgerror2','#msgok2','#bsave2');
		return false;
	}); 
</script>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>