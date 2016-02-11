<div id="profile01">
	<div>
    	<span id="avatargeneral"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.(empty($D->u->avatar)?'default.jpg':$D->u->avatar); ?>"></span>
        <span id="avatarmedium"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min1/'.(empty($D->u->avatar)?'default.jpg':$D->u->avatar); ?>"></span>
        <span id="avatarmini"><img src="<?php echo $C->SITE_URL.$C->FOLDER_AVATAR.'min2/'.(empty($D->u->avatar)?'default.jpg':$D->u->avatar); ?>"></span>
    </div>
    
    <div>
    	<div class="menuglobal">
            <a href="<?php echo $C->SITE_URL.$D->u->username?>/activity">
            <div class="menuoption <?php echo($D->optionactive==1?'act':'inact')?>">
                <div class="icopc"><span title="<?php echo $this->lang('profile_vmenu_opc_activity');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomenuactivity20.png"></span></div>
                <div class="txtopc"><?php echo $this->lang('profile_vmenu_opc_activity');?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
            
        <hr>
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL.$D->u->username?>/items">
            <div class="menuoption <?php echo($D->optionactive==2?'act':'inact')?>">
                <div class="icopc"><span title="<?php echo $this->lang('profile_vmenu_opc_photos');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoitems.png"></span></div>
                <div class="txtopc"><?php echo $this->lang('profile_vmenu_opc_photos');?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
            
        <hr>
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL.$D->u->username?>/likes">
            <div class="menuoption <?php echo($D->optionactive==3?'act':'inact')?>">
                <div class="icopc"><span title="<?php echo $this->lang('profile_vmenu_opc_favorites');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomenufavorites20.png"></span></div>
                <div class="txtopc"><?php echo $this->lang('profile_vmenu_opc_favorites');?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
            
        <hr>
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL.$D->u->username?>/messages">
            <div class="menuoption <?php echo($D->optionactive==5?'act':'inact')?>">
                <div class="icopc"><span title="<?php echo $this->lang('profile_vmenu_opc_messages');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomessages.png"></span></div>
                <div class="txtopc"><?php echo $this->lang('profile_vmenu_opc_messages');?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
            
        <hr>
        
        <div class="menuglobal">
            <a href="<?php echo $C->SITE_URL.$D->u->username?>/info">
            <div class="menuoption <?php echo($D->optionactive==4?'act':'inact')?>">
                <div class="icopc"><span title="<?php echo $this->lang('profile_vmenu_opc_userinformation');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icomenuinfo20.png"></span></div>
                <div class="txtopc"><?php echo $this->lang('profile_vmenu_opc_userinformation');?></div>
                <div class="sh"></div>
            </div>
            </a>
        </div>
            
        <hr>
        
    </div>

</div>