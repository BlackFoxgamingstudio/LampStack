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
            	<div class="title mrg20T"><?php echo $this->lang('profile_followers_title'); ?></div>
                
                <?php if ($D->numfollower == 0) { ?>
                
                <div class="mrg20T txtsize00"><?php echo $this->lang('profile_followers_nofollowers'); ?></div>
            	
                <?php } else { ?>
                
                <div><?php echo $D->htmlFollowers?></div>
                
				<?php if ($D->totalfollowers>$C->NUM_FOLLOWERS_PAGE) { ?>
                
                <div id="moreitems"></div>
                
                <div><input name="numitems" type="hidden" id="numitems" value="<?php echo $D->numfollower?>" /></div>
                
                <div id="moreitemsbar" class="mrg20T mrg10B">
                    <div class="centered">
                    	<span id="bmore" class="bgrey2 rounded"><?php echo $this->lang('global_txt_moreitems')?></span>
                        <span id="morepreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif" /></span>
                    </div>
                </div>
                <script>
					var idu = <?php echo $D->u->iduser ?>;
					var itemperpage = <?php echo $C->NUM_FOLLOWERS_PAGE ?>;
					var txt_norequest = '<?php echo $this->lang('global_txt_no_request') ?>';
                    $('#bmore').click(function(){
                        reloadinfo('followers')
                        return false;
                    });
                </script>
        
                <?php } ?>
                
                
            
            	<?php } ?>
            
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