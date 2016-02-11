<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>
<script type="text/javascript" src="<?php echo $C->SITE_URL?>themes/default/js/js_admin.js"></script>
<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1-admin"><?php $this->load_template('_verticalmenu-admin.php'); ?></div>
        
        <div id="column2-admin">
		
			<div id="dashboard-admin2">

				<div class="title"><?php echo $this->lang('admin_theme_title'); ?></div>
                
                <div class="editarea">
                
                    <div class="subtitle"><?php echo $this->lang('admin_theme_subtitle1');?></div>
                    <hr />
                    
                    <div id="form01">
                    
                      <form id="formlang" name="formlang" method="post" action="">
                        <div class="mrg10T grey1"><?php echo $this->lang('admin_theme_txt1')?></div>
                        <div class="mrg10T">
                        <select name="theme" id="theme" class="combobox">
                        <?php foreach($D->listThemes as $k=>$v) {?>
                        	<option value="<?php echo($k)?>" <?php echo($k==$D->themeDefault?'selected="selected"':'')?>><?php echo($v)?></option>
                        <?php } ?>
                        </select>
                        </div>
                                            
                        <div id="msgerror1" class="redbox"></div>
                        <div id="msgok1" class="yellowbox"></div>
                        <div class="mrg10T mrg5B">
                        <input type="submit" name="bsave1" id="bsave1" value="<?php echo $this->lang('admin_txt_bsave')?>" class="bblue hand"/>
                        </div>
                        
                        </form>
                        
                    </div>
				</div>

            </div>
        
        
        </div>
        
        <div class="sh"></div>
    
    </div>
            
</div>

<script>
	var admin_norequest = '<?php echo $this->lang('admin_no_request');?>';
	
	$('#bsave1').click(function(){
		updateTheme('#msgerror1','#msgok1','#bsave1');
		return false;
	});

</script>     

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>