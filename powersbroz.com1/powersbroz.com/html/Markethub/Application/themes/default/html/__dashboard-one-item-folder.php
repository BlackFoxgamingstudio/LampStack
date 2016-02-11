<div id="oneph_<?php echo $D->f->code?>" class="onephotodetails">
	<div class="theimage"><a class="img_<?php echo $D->f->code?>" href="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.$D->f->imageitem?>"><img src="<?php echo $C->SITE_URL.$C->FOLDER_PHOTOS.'min1/'.$D->f->imageitem?>" class="imagen"></a></div>
    
	<div class="info">
    	<div id="headonephoto_<?php echo $D->f->code?>">
        	<div><span class="typeitemnormal"><?php echo $D->typeitem?></span></div>
            <div id="tf_<?php echo $D->f->code?>" class="titlep"><?php echo ($D->f->title)?></div>
            <div id="df_<?php echo $D->f->code?>" class="descripp"><?php echo ($D->f->description)?></div>
         </div>
        

        <div id="formonephoto_<?php echo $D->f->code?>" class="hide mrg10B">
            <form name="form1" method="post" action="">
    
            <div class="grey1"><?php echo $this->lang('dashboard_myitems_tab2_txttitlephoto')?></div>
            <div><input name="namephoto_<?php echo $D->f->code?>" type="text" class="boxinput withbox" id="namephoto_<?php echo $D->f->code?>" value="<?php echo ($D->f->title)?>"></div>
            
            <div class="mrg10T grey1"><?php echo $this->lang('dashboard_myitems_tab2_txtdescripphoto')?></div>
            <div>
              <textarea name="description_<?php echo $D->f->code?>" rows="3" class="boxinput withbox" id="description_<?php echo $D->f->code?>"><?php echo ($D->f->description)?></textarea></div>
            
            <div id="msgerror_<?php echo $D->f->code?>" class="redbox"></div>
            
            <div class="mrg5B"><input type="submit" name="bsave_<?php echo $D->f->code?>" id="bsave_<?php echo $D->f->code?>" value="<?php echo $this->lang('dashboard_myitems_tab1_txtupdate')?>" class="bblue hand"/> <span id="bcancel_<?php echo $D->f->code?>" class="linkblue hand mrg20L"><a onclick="canceleditphoto('<?php echo $D->f->code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtcancel')?></a></span></div>
            
            </form>
        </div>


        <div class="agregados">
            <div><span class="grey2"><?php echo $this->lang('dashboard_myitems_tab1_txtnumviews')?>:</span> <span><?php echo $D->f->numviews?></span></div>
            <div><span class="grey2"><?php echo $this->lang('dashboard_myitems_tab1_txtnumcomments')?>:</span> <span><?php echo $D->f->numcomments?></span></div>
            <div><span class="grey2"><?php echo $this->lang('dashboard_myitems_tab1_txtnumfavorites')?>:</span> <span><?php echo $D->f->numlikes?></span></div>
        </div>
        
        
        <div class="mrg10T">
            <span class="linkblue mrg10R hand"><a onclick="editphoto('<?php echo $D->f->code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtedit')?></a></span>
            <span class="linkblue mrg10R hand"><a onclick="deleteItem('<?php echo $D->f->code?>')"><?php echo $this->lang('dashboard_myitems_tab1_txtdelete')?></a></span>
        </div>        
        
        
    </div>
</div>
<script>
	$('#bsave_<?php echo $D->f->code?>').click(function(){
		updateItem('#msgerror_<?php echo $D->f->code?>','#bsave_<?php echo $D->f->code?>','<?php echo $D->f->code?>');
		return false;
	});
	
	$('.img_<?php echo $D->f->code?>').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
		
	});
</script>