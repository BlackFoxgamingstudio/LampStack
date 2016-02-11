<script>
var txtloading = '<?php echo $this->lang('global_txtloading'); ?>';
var txtclose = '<?php echo $this->lang('global_txtclose'); ?>';
</script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_dashboard.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/jquery.magnific-popup.js"></script>
<div id="spacemyphoto">
	<div class="linkblue">&laquo; <a href="<?php echo $C->SITE_URL?>dashboard/myitems"><?php echo $this->lang('dashboard_myitems_tab1_txtviewmorealbum')?></a></div>
	<div class="namealb"><?php echo $D->a_name ?></div>
    
    <div class="descripalb"><?php echo $D->a_description ?></div>
    
    
    <?php if (empty($D->htmlPhotos)) { ?>
    
    <div class="centered">
    	<div class="mrg30T txtsize00"><?php echo $this->lang('dashboard_myitems_tab1_albumnophotos')?></div>
        <div class="mrg30T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2/a:<?php echo $D->codealbum?>" class="undecorated"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab1_baddphoto')?></span></a></div>
        <div class="mrg30T"><?php echo $this->lang('dashboard_myitems_or')?></div>
        <div class="mrg30T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3/a:<?php echo $D->codealbum?>" class="undecorated"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab1_baddvideo')?></span></a></div>
    </div>
    
    <?php } else { ?>
    
    <div class="mrg20T"><?php echo $D->htmlPhotos?></div>
    
    <?php } ?>


</div>
<script>
	var msgalert = '<?php echo $this->lang('dashboard_myitems_tab1_txtmsgdeletephoto')?>';
	var dashboard_norequest = '<?php echo $this->lang('dashboard_no_request')?>';
	var msg_noname = '<?php echo $this->lang('dashboard_myitems_tab2_msg10')?>';
	var norequest = '<?php echo $this->lang('dashboard_no_request')?>';
</script>