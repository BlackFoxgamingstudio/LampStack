<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_basic.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_dashboard.js"></script>
<div id="spacemyphoto">

	<div class="tabtitle"><?php echo $this->lang('dashboard_myitems_tab4_title'); ?></div>

    <div id="formedit">
    	<form name="form1" method="post" action="">

        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab4_txtname')?></div>
        <div><input name="namealbum" type="text" class="boxinput withbox" id="namealbum"></div>
        
        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab4_descalbum')?></div>
        <div><textarea name="description" rows="3" class="boxinput withbox" id="description"></textarea></div>
        
        <div id="msgerror1" class="redbox"></div>
        
        <div class="mrg10T mrg5B"><input type="submit" name="bsave1" id="bsave1" value="<?php echo $this->lang('dashboard_myitems_tab4_bsubmit')?>" class="bblue hand"/></div>
        
    	</form>
        
    </div>
    
    <div id="msgok1" class="hide centered">
    	<div class="mrg30T txtsize04 bold"><?php echo $this->lang('dashboard_myitems_tab4_ok_msg01')?></div>
    	<div class="mrg40T txtsize00"><?php echo $this->lang('dashboard_myitems_tab4_ok_msg02')?> <?php echo $this->lang('dashboard_myitems_tab4_ok_msg03')?> <span class="linkblue"><a href="" id="linkaddphotos"><?php echo $this->lang('dashboard_myitems_tab4_ok_msg04')?></a></span> <span><?php echo $this->lang('dashboard_myitems_tab4_ok_msg06')?></span> <span class="linkblue"><a href="" id="linkaddvideos"><?php echo $this->lang('dashboard_myitems_tab4_ok_msg07')?></a></span>.</div>
    	<div class="mrg50T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:4" class="undecorated"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab4_ok_msg05')?></span></a></div>
    </div>


</div>
<script>
	var msg04 = '<?php echo $this->lang('dashboard_myitems_tab4_msg1')?>';
	$('#bsave1').click(function(){
		saveFolder('#msgerror1','#msgok1','#bsave1');
		return false;
	});
</script>