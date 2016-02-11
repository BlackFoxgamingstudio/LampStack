<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="search">
    
            <div class="title"><?php echo $this->lang('global_directory_items_title'); ?></div>

			<div class="mrg10T">
                <div class="filters1">
                    <span class="bold"><?php echo $this->lang('global_directory_items_txt_type'); ?></span>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:0" class="undecorated"><span class="minibutton mrg5L <?php echo($D->typeitems==0?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_all'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:1" class="undecorated"><span class="minibutton mrg10L <?php echo($D->typeitems==1?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_photos'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:2" class="undecorated"><span class="minibutton mrg10L <?php echo($D->typeitems==2?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_gifs'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:3" class="undecorated"><span class="minibutton mrg10L <?php echo($D->typeitems==3?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_videos'); ?></span></a>
                </div>
                
                <div class="filters2">
                    <span class="bold"><?php echo $this->lang('global_directory_items_txt_orderby'); ?></span>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:0" class="undecorated"><span class="minibutton mrg5L <?php echo($D->orderby==0?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_date'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:1" class="undecorated"><span class="minibutton mrg10L <?php echo($D->orderby==1?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_likes'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:2" class="undecorated"><span class="minibutton mrg10L <?php echo($D->orderby==2?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_views'); ?></span></a>
                </div>
                
                <div class="filters3">
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:<?php echo $D->orderby?>/a:0" class="undecorated"><span class="minibutton <?php echo($D->ascendent==0?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_descending'); ?></span></a>
                    <a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:<?php echo $D->orderby?>/a:1" class="undecorated"><span class="minibutton mrg10L <?php echo($D->ascendent==1?'activo':'')?>"><?php echo $this->lang('global_directory_items_txt_ascending'); ?></span></a>
                </div>
            
	            <div class="sh"></div>
            </div>

			<?php if ($D->numphotos<=0) {?>
            
				<div class="msgerror"><?php echo $this->lang('global_search_noresult'); ?></div>
            
            <?php } else { ?>

                <div id="listresults" class="mrg20T"><?php echo $D->htmlPhotos; ?></div>
                
                <div class="sh"></div>
                
                <?php if ($D->totalPag > 1) { ?>
                
                <div id="pagination">
                
                <?php if ($D->pageCurrent != 1) { ?>
                    <span><a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:<?php echo $D->orderby?>/a:<?php echo $D->ascendent?>/p:<?php echo($D->pageCurrent - 1)?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-left.png"></a></span>
                <?php } ?>
                    
                <?php for($i=$D->firstPage; $i<=$D->lastPage; $i++) { ?>
                    <?php if ($i == $D->pageCurrent) {?>
                    <span class="current"><?php echo $i ?></span>
                    <?php } else {?>
                    <span class="nocurrent"><a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:<?php echo $D->orderby?>/a:<?php echo $D->ascendent?>/p:<?php echo $i?>"><?php echo $i?></a></span>
                    <?php } ?>			
                <?php } ?>
                    
                <?php if ($D->pageCurrent != $D->lastPage) { ?>
                    <span><a href="<?php echo $C->SITE_URL?>directory/items/t:<?php echo $D->typeitems?>/o:<?php echo $D->orderby?>/a:<?php echo $D->ascendent?>/p:<?php echo($D->pageCurrent + 1)?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-right.png"></a></span>
                <?php } ?>
                    
                </div>
                
                <div class="sh"></div>
                
                <?php } ?>
                
                
              <?php } ?>
            
        </div>
    
    </div>
            
</div>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>