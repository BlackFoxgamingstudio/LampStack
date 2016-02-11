<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_basic.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_dashboard.js"></script>
<div id="spacemyphoto">
	<div class="tabtitle"><?php echo $this->lang('dashboard_myitems_tab3_title'); ?></div>
    
    <div id="formedit">
    	<form name="form1" method="post" action="">
    
    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab3_txtalbum')?></div>
		<?php if ($D->numalbums>0) { ?>
    	
        <div><select name="album" id="album" class="combobox withbox">
        
        <?php foreach ($D->allAlbums as $onealbum) { ?>
            <option value="<?php echo $onealbum->idalbum?>" <?php echo($onealbum->code==$D->codealbum?'selected="selected"':'')?>><?php echo stripslashes($onealbum->name)?></option>
        <?php } ?>
        
        </select></div>
        <input name="withoutAlbum" type="hidden" id="withoutAlbum" value="0" />
        
        <?php } else { ?>
        
        <div><input name="album" type="text" class="boxinput withbox" id="album" value="My Items - <?php echo date($this->lang('global_format_date_simple'))?>"></div>
        <input name="withoutAlbum" type="hidden" id="withoutAlbum" value="1" />
        
        <?php } ?>

		
        <div id="spacevideo">
        	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab3_txturlvideo')?></div>
            <div><input type="text" name="urlvideo" id="urlvideo"  class="boxinput withbox"></div>
            
            <div id="msgerror" class="redbox"></div>
            
            <div class="mrg10T mrg5B"><input type="submit" name="bpreview" id="bpreview" value="<?php echo $this->lang('dashboard_myitems_tab3_bpreview')?>" class="byellow hand"></div>
        </div>
        
        
        <div id="msgvideoyt" class="hide mrg20T mrg30B"></div>
        
        
    	</form>
        
    </div>
    
    <div id="msgok" class="hide centered">
    	<div class="mrg30T txtsize04 bold"><?php echo $this->lang('dashboard_myitems_tab3_ok_msg01')?></div>
    	<div class="mrg40T txtsize00"><?php echo $this->lang('dashboard_myitems_tab3_ok_msg02')?></div>
    	<div class="mrg50T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3" class="undecorated" id="linkother"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab3_ok_msg03')?></span></a></div>
    </div>

</div>
<script>	
	var norequest = '<?php echo $this->lang('dashboard_no_request')?>';
	var vmsg1 = '<?php echo $this->lang('dashboard_myitems_tab3_txtmsg1')?>';
	var vmsg2 = '<?php echo $this->lang('dashboard_myitems_tab3_txtmsg3')?>';
	var vmsg3 = '<?php echo $this->lang('dashboard_myitems_tab3_txtmsg4')?>';
	$('#bpreview').click(function(){
		previewVideo('#msgerror','#msgok','#bpreview','#spacevideo');
		return false;
	});
</script>