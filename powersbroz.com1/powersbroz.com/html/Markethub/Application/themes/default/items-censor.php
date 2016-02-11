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
                	
                    <div class="centered mrg40T"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/imgcensor.png"></div>
                    <div class="mrg10T txtsize15 centered bold mrg20B"><?php echo $this->lang('profile_censor_photocensor');?></div>

                
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
	var msgnocomment = '<?php echo $this->lang('profile_photos_txtnocomment');?>';
	$('#bsave').click(function(){
		saveComment('#msgerror', '#bsave');
		return false;
	});

</script>
<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>