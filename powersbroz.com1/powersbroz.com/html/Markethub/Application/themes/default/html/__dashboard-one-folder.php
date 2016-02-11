<?php
$miniphoto = array();
$typeitem = array();
for($i=0; $i<6; $i++)
{
	$miniphoto[$i] = '';
	if (isset($D->a_miniphotos[$i])) {
		$miniphoto[$i]='<img src="'.$C->SITE_URL.$C->FOLDER_PHOTOS.'/min2/'.$D->a_miniphotos[$i]->imageitem.'">';
		switch($D->a_miniphotos[$i]->typeitem) {
			case 1:
				$typeitem[$i] = $this->lang('global_txt_type1');
				break;
			case 2:
				$typeitem[$i] =  $this->lang('global_txt_type2');
				break;
			case 3:
				$typeitem[$i] =  $this->lang('global_txt_type3');
				break;			
		}
	}
}
?>

<div id="a_<?php echo $D->a_code?>" class="onealbum">
	<div id="headonealbum_<?php echo $D->a_code?>" class="headonealbum">
		<div id="na_<?php echo $D->a_code?>" class="namealbum"><?php echo $D->a_name?></div>
        <div id="da_<?php echo $D->a_code?>" class="descalbum"><?php echo $D->a_description?></div>
        <div class="numitems"><?php echo $D->a_numitems?> <?php echo($D->a_numitems==1?$this->lang('dashboard_myitems_tab1_txtphoto'):$this->lang('dashboard_myitems_tab1_txtphotos')) ?></div>
    </div>
    <div id="formonealbum_<?php echo $D->a_code?>" class="hide mrg10B">
    	<form name="form1" method="post" action="">

        <div class="grey1"><?php echo $this->lang('dashboard_myphotos_tab3_txtname')?></div>
        <div><input name="namealbum_<?php echo $D->a_code?>" type="text" class="boxinput withbox" id="namealbum_<?php echo $D->a_code?>" value="<?php echo $D->a_name?>"></div>
        
        <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myphotos_tab3_descalbum')?></div>
        <div>
          <textarea name="description_<?php echo $D->a_code?>" rows="3" class="boxinput withbox" id="description_<?php echo $D->a_code?>"><?php echo $D->a_description?></textarea></div>
        
        <div id="msgerror_<?php echo $D->a_code?>" class="redbox"></div>
        
        <div class="mrg5B"><input type="submit" name="bsave_<?php echo $D->a_code?>" id="bsave_<?php echo $D->a_code?>" value="<?php echo $this->lang('dashboard_myitems_tab1_txtupdate')?>" class="bblue hand"/> <span id="bcancel_<?php echo $D->a_code?>" class="linkblue hand mrg20L"><a onclick="canceleditfolder('<?php echo $D->a_code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtcancel')?></a></span></div>
        
    	</form>
    </div>
    
	<a href="<?php echo $C->SITE_URL?>dashboard/myitems/a:<?php echo $D->a_code ?>">
    <div id="minisalbum">
    	<?php for($i=0; $i<6; $i++) { ?>
        <div class="spacephoto"><div class="oneminiphoto"><?php if (isset($miniphoto[$i])) echo $miniphoto[$i]?><span class="typeitem"><?php if (isset($typeitem[$i])) echo $typeitem[$i]?></span></div></div>
        <?php } ?>
        <div class="sh"></div>
    </div>
    </a>
    
    <div class="footonealbum">
    	<div>
        	<span class="linkblue mrg10R"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2/a:<?php echo $D->a_code?>"><?php echo $this->lang('dashboard_myitems_tab1_txtaddphoto')?></a></span>
            <span class="linkblue mrg10R"><a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3/a:<?php echo $D->a_code?>"><?php echo $this->lang('dashboard_myitems_tab1_txtaddvideo')?></a></span>
            <span class="linkblue mrg10R hand"><a onclick="editfolder('<?php echo $D->a_code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtedit')?></a></span>
            <span class="linkblue mrg10R hand"><a onclick="deleteFolder('<?php echo $D->a_code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtdelete')?></a></span>
        </div>
    </div>
</div>
<script>
	$('#bsave_<?php echo $D->a_code?>').click(function(){
		updateFolder('#msgerror_<?php echo $D->a_code?>','#bsave_<?php echo $D->a_code?>','<?php echo $D->a_code?>');
		return false;
	});
</script>