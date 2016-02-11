<div id="id_<?php echo $D->g->code; ?>" class="onephoto">
	<a class="img_<?php echo $D->g->code?>" href="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->g->imageitem?>" title="<?php echo $D->g->title?>">
    <div class="thephoto">
    	<img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min1/'.$D->g->imageitem?>">
    </div>
	</a>
    <div class="moredata centered">
    	<div><?php echo($D->g->numcensors)?> <img src="<?php echo $C->SITE_URL?>themes/default/imgs/mini-ico-red.png"></div>
        <div><span><a href="javascript:restoreitem('<?php echo($D->g->code)?>');" title="<?php echo($this->lang('admin_censors_alt1'));?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/ico-restorephoto.png"></a></span> <span><a href="javascript:deleteitem('<?php echo($D->g->code)?>');" title="<?php echo($this->lang('admin_censors_alt2'));?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/ico-deletephoto.png"></a></span></div>
     </div>
</div>

<script>

	$('.img_<?php echo $D->g->code?>').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

</script>