<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_dashboard.js"></script>
<div id="spacemyphoto">
	<div class="tabtitle"><?php echo $this->lang('dashboard_myitems_tab1_title'); ?></div>
    
    <?php if (empty($D->htmlAlbums)) { ?>
    
    <div class="centered">
    	<div class="mrg30T txtsize00"><?php echo $this->lang('dashboard_myitems_tab1_nophotos')?></div>
        <div class="mrg30T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2" class="undecorated"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab1_baddphoto')?></span></a></div>
        <div class="mrg30T"><?php echo $this->lang('dashboard_myitems_or')?></div>
        <div class="mrg30T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3" class="undecorated"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab1_baddvideo')?></span></a></div>
    </div>
    
    <?php } else { ?>
    
    	<div class="mrg20T"><?php echo $D->htmlAlbums?></div>
    
		<?php if ($D->totalalbums>$C->NUM_ALBUMS_PAGE) { ?>
        
        <div id="moreitems"></div>
        
        <div><input name="numitems" type="hidden" id="numitems" value="<?php echo $D->numAlbums?>" /></div>
        
        <div id="moreitemsbar" class="mrg20T mrg10B">
            <div class="centered">
                <span id="bmore" class="bgrey2 rounded"><?php echo $this->lang('global_txt_moreitems')?></span>
                <span id="morepreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif" /></span>
            </div>
        </div>
        <script>
            var idu = <?php echo $this->user->id ?>;
            var itemperpage = <?php echo $C->NUM_ALBUMS_PAGE ?>;
            var txt_norequest = '<?php echo $this->lang('global_txt_no_request') ?>';
            $('#bmore').click(function(){
                reloadinfo('albums')
                return false;
            });
        </script>
    
        <?php } ?>
    
    
    
    
    
    <?php } ?>

</div>
<script>
	var msgalert = '<?php echo $this->lang('dashboard_myitems_tab1_txtmsgdelete')?>';
	var dashboard_norequest = '<?php echo $this->lang('dashboard_no_request')?>';
	var msg_noname = '<?php echo $this->lang('dashboard_myitems_tab4_msg1')?>';
	var norequest = '<?php echo $this->lang('dashboard_no_request')?>';
</script>