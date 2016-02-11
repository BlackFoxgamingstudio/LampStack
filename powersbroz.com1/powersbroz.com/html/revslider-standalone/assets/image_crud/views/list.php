<div id="fine-uploader"></div>
<div class="clear"></div>
<div id='ajax-list'>
	<?php if(!empty($photos)){?>
	<ul class='photos-crud'>
	<?php foreach($photos as $photo_num => $photo){?>
			<li id="photos_<?php echo $photo->$primary_key; ?>">
				<div class='photo-box' data-id="<?php echo $photo->$primary_key; ?>" data-url="<?php echo $photo->image_url?>">
					<a href="#" class="select-handle"><img src='<?php echo $photo->thumbnail_url?>' width='195' height='130' class='basic-image' /></a>
					<?php if($title_field !== null){ ?>
					<textarea class="ic-title-field" data-id="<?php echo $photo->$primary_key; ?>" autocomplete="off" ><?php echo $photo->$title_field; ?></textarea>
					<div class="clear"></div><?php }?>
					<?php if($has_priority_field){?><div class="move-box"></div><?php }?>
					<a href="<?php echo $photo->image_url?>" class="button-primary revblue color-box floatL" rel="color-box"><i class="revicon-search-1"></i><?php _e('View'); ?></a>
					<?php if(!$unset_delete){?>
						<a href="<?php echo $photo->delete_url?>" class="button-primary revred delete-anchor floatR"><i class="revicon-trash"></i><?php _e('Delete'); ?></a>
					<?php }?>
					<div class="clear"></div>
				</div>
			</li>
	<?php }?>
		</ul>
		<div class='clear'></div>
	<?php }?>
</div>