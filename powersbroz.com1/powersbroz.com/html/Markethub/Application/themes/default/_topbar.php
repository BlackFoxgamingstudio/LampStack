
<script type="text/javascript" src="<?php $C->SITE_URL ?>themes/default/js/js_search.js"></script>
<div id="topbar">
    <div id="area1">
    	<a href="<?php echo $C->SITE_URL?>">
        <div id="isotipo"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/isotipo.png"></div>
        <div id="logo"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/logo.png"></div>
        </a>
        
        <div id="boxsearch"><input name="topsearch" type="text" class="box-input" id="topsearch" placeholder="<?php echo $this->lang('global_txt_search');?>" x-webkit-speech="x-webkit-speech" onwebkitspeechchange="topSearch();"></div>
        <div class="sh"></div>
    </div>


    <div id="area2">

    	<div id="icodashtop">
            <a href="<?php echo $C->SITE_URL.'register';?>"><span class="icotop" title="<?php echo $this->lang('global_tmenu_opc_register');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icoregister.png"></span></a>
        </div>
    	<div id="icodashtop">
            <a href="<?php echo $C->SITE_URL.'login';?>"><span class="icotop" title="<?php echo $this->lang('global_tmenu_opc_login');?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icologin.png"></span></a>
        </div>
    	<div class="sh"></div>
        
    </div>


    <div class="sh"></div>
    
</div>

<div id="search-container"></div>

<div class="sh"></div>
<script> $("#topsearch").on('keyup', topSearch); </script>