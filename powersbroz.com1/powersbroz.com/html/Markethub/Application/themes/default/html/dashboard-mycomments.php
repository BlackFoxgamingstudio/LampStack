<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_dashboard.js"></script>
<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1"><?php $this->load_template('_verticalmenu-dashboard.php'); ?></div>
        
        <div id="column2">
		

			<div id="dashboard2">

				<div id="listcomments">
                
                    <div class="title"><?php echo $this->lang('dashboard_mycomments_title'); ?></div>
                    
                    <?php if ($D->numcomments == 0) { ?>
                    
                    <div class="mrg20T txtsize00"><?php echo $this->lang('dashboard_mycomments_nocomments'); ?></div>
                    
                    <?php } else { ?>
                    
                    <?php echo $D->htmlComments?>
                    
                    <?php if ($D->totalcomments>$C->NUM_COMMENTS_DASH_PAGE) { ?>
                    
                    <div id="moreitems"></div>
                    
                    <div><input name="numitems" type="hidden" id="numitems" value="<?php echo $D->numcomments?>" /></div>
                    
                    <div id="moreitemsbar" class="mrg20T mrg10B">
                        <div class="centered">
                            <span id="bmore" class="bgrey2 rounded"><?php echo $this->lang('global_txt_moreitems')?></span>
                            <span id="morepreload" class="hide"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/preload.gif" /></span>
                        </div>
                    </div>
                    <script>
                        var idu = <?php echo $this->user->id ?>;
                        var itemperpage = <?php echo $C->NUM_COMMENTS_DASH_PAGE ?>;
                        var txt_norequest = '<?php echo $this->lang('global_txt_no_request') ?>';
                        $('#bmore').click(function(){
                            reloadinfo('comments')
                            return false;
                        });
                    </script>
            
                    <?php } ?>
                    
    
                    <?php } ?>
                    
                    
                </div>

            </div>
        
        
        </div>
        
        <div id="divseparator" class="sh"></div>
        
        <div id="column3"><?php $this->load_template('_accessories-dashboard.php'); ?></div>
        
        <div class="sh"></div>
    
    </div>
            
</div>    

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>