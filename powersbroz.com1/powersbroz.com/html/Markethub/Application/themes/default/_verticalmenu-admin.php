<div id="dashboard-admin1">
	<div class="titlemenu"><span><?php echo $this->lang('admin_menu_title')?></span></div>
    
    <div>
		<div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/general">
            <div class="menuoption <?php echo($D->optionactive_admin==1?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_general')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>
        
          
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/users">
            <div class="menuoption <?php echo($D->optionactive_admin==3?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_users')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>  
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/censors">
            <div class="menuoption <?php echo($D->optionactive_admin==7?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_censors')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>
        
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/themes">
            <div class="menuoption <?php echo($D->optionactive_admin==4?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_themes')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>
        
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/languages">
            <div class="menuoption <?php echo($D->optionactive_admin==5?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_languages')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>    
        
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/ads">
            <div class="menuoption <?php echo($D->optionactive_admin==6?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_ads')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr>
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL?>admin/pages">
            <div class="menuoption <?php echo($D->optionactive_admin==2?'act':'inact')?>">
                <div><?php echo $this->lang('admin_menu_pages')?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
        
        <hr> 

        
    </div>


</div>