<?php
$miniphoto = array();
$typeitem = array();
for($i=0; $i<6; $i++)
{
	$miniphoto[$i]='';
	if (isset($D->miniphotos[$i])) {
		$miniphoto[$i]='<img src="'.$C->SITE_URL.$C->FOLDER_PHOTOS.'/min2/'.$D->miniphotos[$i]->imageitem.'">';
		switch($D->miniphotos[$i]->typeitem) {
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

<div class="onealbum">
	<div class="headonealbum">
		<div class="namealbum"><?php echo $D->namealbum?></div>
        <div class="descalbum"><?php echo $D->description?></div>
    </div>
    
    
	<a href="<?php echo $C->SITE_URL.$D->usernameProfile?>/items/folder/<?php echo $D->codealbum ?>">
    <div id="minisalbum">
    	<?php for($i=0; $i<6; $i++) { ?>
        <div class="spacephoto"><div class="oneminiphoto"><?php if (isset($miniphoto[$i])) echo $miniphoto[$i]?><span class="typeitem"><?php if (isset($typeitem[$i])) echo $typeitem[$i]?></div></div>
        <?php } ?>
        <div class="sh"></div>
    </div>
    </a>
    
    <div class="footonealbum">
		<div class="numphotos"><?php echo $D->numitems?> <?php echo($D->numitems==1?$this->lang('profile_items_txtphoto'):$this->lang('profile_items_txtphotos')) ?></div>
    </div>
</div>