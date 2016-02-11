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
            	<div class="title mrg20T"><?php echo $this->lang('profile_userinfo_title'); ?></div>
            	
                <div class="subtitle mrg20T"><?php echo $this->lang('profile_userinfo_subtitle1'); ?></div>
                <div class="mrg10T"><?php echo $D->aboutme?></div>

                <div class="subtitle mrg20T"><?php echo $this->lang('profile_userinfo_subtitle2'); ?></div>
                <div class="mrg10T"><?php echo $D->gender; ?></div>

                <div class="subtitle mrg20T"><?php echo $this->lang('profile_userinfo_subtitle3'); ?></div>
                <div class="mrg10T"><?php echo $D->birth; ?></div>

                <div class="subtitle mrg20T"><?php echo $this->lang('profile_userinfo_subtitle4'); ?></div>
                <div class="mrg10T"><?php echo $D->location; ?></div>

            
            
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