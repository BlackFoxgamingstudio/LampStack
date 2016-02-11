<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_basic.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_uploadphoto.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL; ?>themes/default/js/js_dashboard.js"></script>
<div id="spacemyphoto">
	<div class="tabtitle"><?php echo $this->lang('dashboard_myitems_tab2_title'); ?></div>
    
    <div id="formedit">
    	<form name="form1" method="post" action="">
    
    	<div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab2_txtalbum')?></div>
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
        
        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab2_txtphoto')?></div>
		<div class="centered"><img src="" id="prwimg" class="previewphotodash hide"></div>
        <div class="mrg5T"><span id="linkUpPhoto" class="onlyblue bold hand"><?php echo $this->lang('dashboard_myitems_tab2_txtupphoto')?></span></div>
        <div id="areapreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif"/></div>
        <input name="loadedimage" type="hidden" id="loadedimage" value="" />
        <input name="didchanges" type="hidden" id="didchanges" value="0" />        
        
        
        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab2_txttitlephoto')?></div>
        <div><input name="title" type="text" class="boxinput withbox" id="title"></div>
        
        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab2_txtdescripphoto')?></div>
        <div><textarea name="description" rows="3" class="boxinput withbox" id="description"></textarea></div>
        
        <div id="msgerror1" class="redbox"></div>
        
        <div class="mrg10T mrg5B"><input type="submit" name="bsave1" id="bsave1" value="<?php echo $this->lang('dashboard_myitems_tab2_bsubmit')?>" class="bblue hand"/></div>
        
    	</form>
        
    </div>
    
    <div id="msgok1" class="hide centered">
    	<div class="mrg30T txtsize04 bold"><?php echo $this->lang('dashboard_myitems_tab2_ok_msg01')?></div>
    	<div class="mrg40T txtsize00"><?php echo $this->lang('dashboard_myitems_tab2_ok_msg02')?></div>
    	<div class="mrg50T"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2" class="undecorated" id="linkother"><span class="bgreen"><?php echo $this->lang('dashboard_myitems_tab2_ok_msg03')?></span></a></div>
    </div>

</div>
<script>
	var msg01 = '<?php echo $this->lang('dashboard_myitems_tab2_txtmsg1')?>';
	var msg02 = '<?php echo $this->lang('dashboard_myitems_tab2_txtmsg2')?>';
	var msg03 = '<?php echo $this->lang('dashboard_myitems_tab2_msg5')?>';	
	var msg04 = '<?php echo $this->lang('dashboard_myitems_tab2_msg4')?>';
	var msg05 = '<?php echo $this->lang('dashboard_myitems_tab2_msg10')?>';
	var norequest = '<?php echo $this->lang('dashboard_no_request')?>';
	
	$('#linkUpPhoto').uploadPhoto(1,'<?php echo $C->FOLDER_TMP?>','_pho_',msg02,msg01);
	$('#bsave1').click(function(){
		savePhoto('#msgerror1','#msgok1','#bsave1');
		return false;
	});
</script>