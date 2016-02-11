<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1"><?php $this->load_template('_verticalmenu-dashboard.php'); ?></div>
        
        <div id="column2">
		
            <div id="dashboard2">
            
            	<div id="myphotos">
                
                	<div id="optionsbar">
                    
                    	<a href="<?php echo $C->SITE_URL?>dashboard/myitems" title="<?php echo $this->lang('dashboard_myitems_alt_myphotos')?>"><div class="optiontab"><span class="<?php echo($D->tabactive==1?'active':'')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomyitems.png" class="pdn10T"></span></div></a>
                        <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:2" title="<?php echo $this->lang('dashboard_myitems_alt_addphoto')?>"><div class="optiontab"><span class="<?php echo($D->tabactive==2?'active':'')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddphoto.png"></span></div></a>
                        <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:3" title="<?php echo $this->lang('dashboard_myitems_alt_addvideo')?>"><div class="optiontab"><span class="<?php echo($D->tabactive==3?'active':'')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddvideo.png"></span></div></a>
                        <a href="<?php echo $C->SITE_URL?>dashboard/myitems/tab:4" title="<?php echo $this->lang('dashboard_myitems_alt_addalbum')?>"><div class="optiontab"><span class="<?php echo($D->tabactive==4?'active':'')?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoaddfolder.png"></span></div></a>
                        <div class="sh"></div>
                    
                    </div>
                    
                    
                    <div>
                    
					<?php $this->load_template($D->filetoLoad); ?>
                    
                    </div>
            
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