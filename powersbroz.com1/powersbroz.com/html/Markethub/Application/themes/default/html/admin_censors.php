<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script>
var txtloading = '<?php echo $this->lang('global_txtloading'); ?>';
var txtclose = '<?php echo $this->lang('global_txtclose'); ?>';
var txtrestore = '<?php echo $this->lang('admin_censors_msgalertrestore'); ?>';
var txtdelete = '<?php echo $this->lang('admin_censors_msgalertdelete'); ?>';
</script>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_admin.js"></script>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/jquery.magnific-popup.js"></script>
<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1-admin"><?php $this->load_template('_verticalmenu-admin.php'); ?></div>
        
        <div id="column2-admin">
		
			<div id="dashboard-admin2">

				<div class="title"><?php echo $this->lang('admin_censors_title'); ?></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_censors_subtitle');?></div>
                    <hr />
                    
                    <div class="mrg10T grey1"><?php echo $this->lang('admin_censors_subtitle2')?></div>
                    
                    <div id="listcensor" class="mrg10T">
                    
                    <?php echo $D->htmlCensors?>
                        
                    </div>
                    <div class="sh"></div>
                    
                    <?php if ($D->totalpages > 1) {?>
                    
                    <div id="paginations" class="mrg20T">
                    
                    <?php for ($i=1; $i<=$D->totalpages; $i++) { ?>
                    
                    	<?php if ($i == $D->npage) {?>
                        <span class="pactive"><?php echo $i?></span>
                        <?php } else { ?>
                        <span class="pnoactive"><a href="<?php echo $C->SITE_URL?>admin/censors/p:<?php echo $i?>"><?php echo $i?></a></span>
                        <?php } ?>
                    
                    <?php } ?>
                        
                    </div>
                    
                    <?php } ?>
                    
                    
				</div>

            </div>
        
        
        </div>
        
        <div class="sh"></div>
    
    </div>
            
</div>    

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>