<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script>
var txtloading = '<?php echo $this->lang('global_txtloading'); ?>';
var txtclose = '<?php echo $this->lang('global_txtclose'); ?>';
</script>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_dashboard.js"></script>
<div id="generalspace">
        
	<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/jquery.magnific-popup.js"></script>
    <div id="container">
    
    	<div id="column1"><?php $this->load_template('_verticalmenu-dashboard.php'); ?></div>
        
        <div id="column2">
		
            <div id="dashboard2">


				<div>
                    
                    <a href="<?php echo $C->SITE_URL?>dashboard/myitems" title="<?php echo $this->lang('dashboard_myitems_alt_myphotos')?>"><div class="fleft"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomyitems.png" class="pdn10T"></div></a>
                    <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2" title="<?php echo $this->lang('dashboard_myitems_alt_addphoto')?>"><div class="fleft"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddphoto.png"></div></a>
                    <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3" title="<?php echo $this->lang('dashboard_myitems_alt_addvideo')?>"><div class="fleft"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddvideo.png"></div></a>
                    <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:4" title="<?php echo $this->lang('dashboard_myitems_alt_addalbum')?>"><div class="fleft"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddfolder.png"></div></a>
                    <div class="sh"></div>
                
                </div>
				
                <?php if (!empty($D->htmlResult)) { ?>
                
                <div class="title"><?php echo $this->lang('dashboard_activities_title'); ?></div>
                
                <div><?php echo $D->htmlResult; ?></div>
                
                
				<?php if ($D->totalactivities > $C->NUM_ACTIVITIES_PAGE) { ?>
                
                <div id="moreitems"></div>
                
                <div><input name="numitems" type="hidden" id="numitems" value="<?php echo $D->numactivities?>" /></div>
                
                <div id="moreitemsbar" class="mrg20T mrg10B">
                    <div class="centered">
                    	<span id="bmore" class="bgrey2 rounded"><?php echo $this->lang('global_txt_moreitems')?></span>
                        <span id="morepreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif" /></span>
                    </div>
                </div>
                <script>
					var idu = <?php echo $this->user->id ?>;
					var itemperpage = <?php echo $C->NUM_ACTIVITIES_PAGE ?>;
					var txt_norequest = '<?php echo $this->lang('global_txt_no_request') ?>';
                    $('#bmore').click(function(){
                        reloadinfo('activities');
                        return false;
                    });
                </script>
        
                <?php } ?>
                
                
                <?php } else {?>
                
                <div><?php $this->load_template('__dashboard-welcome.php');?></div>
                
                <?php } ?>

            </div>
        
        </div>
        
        <div id="divseparator" class="sh"></div>
        
        <div id="column3"><?php $this->load_template('_accessories-dashboard.php'); ?></div>
        
        <div class="sh"></div>
    
    </div>
            
</div>    

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>