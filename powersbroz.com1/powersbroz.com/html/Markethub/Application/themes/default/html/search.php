<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="search">
    
            <div class="title"><?php echo $this->lang('global_search_title'); ?></div>
            
            <?php if ($D->errorsearch == 1) { ?>
            
            <div class="msgerror"><?php echo $D->msgerror ?></div>
            
            <?php } else { ?>
            

			<?php if ($D->numphotos<=0) {?>
            
				<div class="msgerror"><?php echo $this->lang('global_search_noresult'); ?></div>
            
            <?php } else { ?>

                <div id="listresults" class="mrg20T"><?php echo $D->htmlPhotos; ?></div>
                
                <div class="sh"></div>
                
                <?php if ($D->totalPag > 1) { ?>
                
                <div id="pagination">
                
                <?php if ($D->pageCurrent != 1) { ?>
                    <span><a href="<?php echo $C->SITE_URL?>search/p:<?php echo($D->pageCurrent - 1)?>/q:<?php echo $D->query?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-left.png"></a></span>
                <?php } ?>
                    
                <?php for($i=$D->firstPage; $i<=$D->lastPage; $i++) { ?>
                    <?php if ($i == $D->pageCurrent) {?>
                    <span class="current"><?php echo $i ?></span>
                    <?php } else {?>
                    <span class="nocurrent"><a href="<?php echo $C->SITE_URL?>search/p:<?php echo $i?>/q:<?php echo $D->query?>"><?php echo $i?></a></span>
                    <?php } ?>			
                <?php } ?>
                    
                <?php if ($D->pageCurrent != $D->lastPage) { ?>
                    <span><a href="<?php echo $C->SITE_URL?>search/p:<?php echo($D->pageCurrent + 1)?>/q:<?php echo $D->query?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-right.png"></a></span>
                <?php } ?>
                    
                </div>
                
                <div class="sh"></div>
                
                <?php } ?>
                
                
              <?php } ?>
            
            
            <?php } //end else error ?>
            
        </div>
    
    </div>
            
</div>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>