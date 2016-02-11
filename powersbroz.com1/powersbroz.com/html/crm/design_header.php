<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */

switch($display_mode){
    case 'mobile':
        if(class_exists('module_mobile',false)){
            module_mobile::render_start($page_title,$page);
        }
        break;
    case 'ajax':

        break;
    case 'iframe':
    case 'normal':
    default:


        ?>

        <!DOCTYPE html>
        <html dir="<?php echo module_config::c('text_direction','ltr');?>">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $page_title; ?></title>

        <?php $header_favicon = module_theme::get_config('theme_favicon','');
            if($header_favicon){ ?>
                <link rel="icon" href="<?php echo htmlspecialchars($header_favicon);?>">
        <?php } ?>

            <link type="text/css" href="<?php echo _BASE_HREF;?>css/smoothness/jquery-ui-1.9.2.custom.min.css?ver=<?php echo _SCRIPT_VERSION;?>" rel="stylesheet" />
            <link rel="stylesheet" href="<?php echo _BASE_HREF;?>css/desktop.css?ver=<?php echo _SCRIPT_VERSION;?>" type="text/css" />
            <link rel="stylesheet" href="<?php echo _BASE_HREF;?>css/styles.css?ver=<?php echo _SCRIPT_VERSION;?>" type="text/css" />
            <?php module_config::print_css(_SCRIPT_VERSION);?>



        <script language="javascript" type="text/javascript">
            // by dtbaker.
            var ajax_search_ini = '';
            var ajax_search_xhr = false;
            var ajax_search_url = '<?php echo _BASE_HREF;?>ajax.php';
        </script>

        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-1.8.3.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-ui-1.9.2.custom.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/timepicker.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/cookie.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/javascript.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <?php module_config::print_js(_SCRIPT_VERSION);?>


        <!--
        Author: David Baker (dtbaker.com.au)
        10/May/2010
        -->
        <script type="text/javascript">
            $(function(){
                init_interface();
                // calendar defaults
                <?php
                switch(strtolower(module_config::s('date_format','d/m/Y'))){
                    case 'd/m/y':
                        $js_cal_format = 'dd/mm/yy';
                        break;
                    case 'y/m/d':
                        $js_cal_format = 'yy/mm/dd';
                        break;
                    case 'm/d/y':
                        $js_cal_format = 'mm/dd/yy';
                        break;
                    default:
                        $js_cal_format = 'yy-mm-dd';
                }
                ?>
                $.datepicker.regional['ucmcal'] = {
                    closeText: '<?php echo addcslashes(_l('Done'),"'");?>',
                    prevText: '<?php echo addcslashes(_l('Prev'),"'");?>',
                    nextText: '<?php echo addcslashes(_l('Next'),"'");?>',
                    currentText: '<?php echo addcslashes(_l('Today'),"'");?>',
                    monthNames: ['<?php echo addcslashes(_l('January'),"'");?>','<?php echo addcslashes(_l('February'),"'");?>','<?php echo addcslashes(_l('March'),"'");?>','<?php echo addcslashes(_l('April'),"'");?>','<?php echo addcslashes(_l('May'),"'");?>','<?php echo addcslashes(_l('June'),"'");?>', '<?php echo addcslashes(_l('July'),"'");?>','<?php echo addcslashes(_l('August'),"'");?>','<?php echo addcslashes(_l('September'),"'");?>','<?php echo addcslashes(_l('October'),"'");?>','<?php echo addcslashes(_l('November'),"'");?>','<?php echo addcslashes(_l('December'),"'");?>'],
                    monthNamesShort: ['<?php echo addcslashes(_l('Jan'),"'");?>', '<?php echo addcslashes(_l('Feb'),"'");?>', '<?php echo addcslashes(_l('Mar'),"'");?>', '<?php echo addcslashes(_l('Apr'),"'");?>', '<?php echo addcslashes(_l('May'),"'");?>', '<?php echo addcslashes(_l('Jun'),"'");?>', '<?php echo addcslashes(_l('Jul'),"'");?>', '<?php echo addcslashes(_l('Aug'),"'");?>', '<?php echo addcslashes(_l('Sep'),"'");?>', '<?php echo addcslashes(_l('Oct'),"'");?>', '<?php echo addcslashes(_l('Nov'),"'");?>', '<?php echo addcslashes(_l('Dec'),"'");?>'],
                    dayNames: ['<?php echo addcslashes(_l('Sunday'),"'");?>', '<?php echo addcslashes(_l('Monday'),"'");?>', '<?php echo addcslashes(_l('Tuesday'),"'");?>', '<?php echo addcslashes(_l('Wednesday'),"'");?>', '<?php echo addcslashes(_l('Thursday'),"'");?>', '<?php echo addcslashes(_l('Friday'),"'");?>', '<?php echo addcslashes(_l('Saturday'),"'");?>'],
                    dayNamesShort: ['<?php echo addcslashes(_l('Sun'),"'");?>', '<?php echo addcslashes(_l('Mon'),"'");?>', '<?php echo addcslashes(_l('Tue'),"'");?>', '<?php echo addcslashes(_l('Wed'),"'");?>', '<?php echo addcslashes(_l('Thu'),"'");?>', '<?php echo addcslashes(_l('Fri'),"'");?>', '<?php echo addcslashes(_l('Sat'),"'");?>'],
                    dayNamesMin: ['<?php echo addcslashes(_l('Su'),"'");?>','<?php echo addcslashes(_l('Mo'),"'");?>','<?php echo addcslashes(_l('Tu'),"'");?>','<?php echo addcslashes(_l('We'),"'");?>','<?php echo addcslashes(_l('Th'),"'");?>','<?php echo addcslashes(_l('Fr'),"'");?>','<?php echo addcslashes(_l('Sa'),"'");?>'],
                    weekHeader: '<?php echo addcslashes(_l('Wk'),"'");?>',
                    dateFormat: '<?php echo $js_cal_format;?>',
                    firstDay: <?php echo module_config::c('calendar_first_day_of_week','1');?>,
                    yearRange: '<?php echo module_config::c('calendar_year_range','-90:+3');?>'
                };
                $.datepicker.setDefaults($.datepicker.regional['ucmcal']);
            });
        </script>


        </head>
        <body id="<?php echo isset($page_unique_id) ? $page_unique_id : 'page';?>" <?php if($display_mode=='iframe') echo ' style="background:#FFF;"';?>>

<?php if($display_mode=='iframe'){ ?>
<div id="iframe">
<?php }else{ ?>
<?php if(_DEBUG_MODE){
    module_debug::print_heading();
} ?>
<div id="holder">


	<div id="header">

        <div>
            <div style="position:absolute; z-index:1004; margin-left:367px;width:293px; display:none;" id="message_popdown">
                <?php if(print_header_message()){
                    ?>
                    <script type="text/javascript">
                        $('#message_popdown').fadeIn('slow');
                        <?php if(module_config::c('header_messages_fade_out',1)){ ?>
                        $(function(){
                            setTimeout(function(){
                                $('#message_popdown').fadeOut();
                            },4000);
                        });
                        <?php } ?>
                    </script>
                        <?php
                } ?>
            </div>
        </div>

        <?php if(_DEMO_MODE && preg_match('#/demo_lite/#',$_SERVER['REQUEST_URI'])){ ?>
        <div style="margin: 10px 0 0 296px;position:absolute;">
            <a href="http://goo.gl/YYgVJ" title="Download Ultimate Client Manager"><img src="http://ultimateclientmanager.com/webimages/like-what-you-see-here.png" border="0" alt="Freelance Database - php client manager"></a>
        </div>
        <?php } ?>

		<div id="header_logo">
            <?php if($header_logo = module_theme::get_config('theme_logo',_BASE_HREF.'images/logo.png')){ ?>
                <a href="<?php echo _BASE_HREF;?>"><img src="<?php echo htmlspecialchars($header_logo);?>" border="0" title="<?php echo htmlspecialchars(module_config::s('header_title','UCM'));?>"></a>
            <?php }else{ ?>
                <a href="<?php echo _BASE_HREF;?>"><?php echo module_config::s('header_title','UCM');?></a>
            <?php } ?>
		</div>
		<?php
		if(module_security::getcred()){
			?>
	    	<div id="profile_info">
			    <?php
                $header_buttons = array();
			    if(module_security::is_logged_in()) {
				    $header_buttons = hook_filter_var( 'header_buttons', $header_buttons );
			    }
			    foreach($header_buttons as $header_button){
				    ?>
				    <a href="#" id="<?php echo $header_button['id'];?>">
				        <i class="fa fa-<?php echo $header_button['fa-icon'];?>"></i>
				        <?php echo $header_button['title'];?>
				    </a>
				    <span class="sep">|</span>
			    <?php
			    }
			    ?>
				<?php echo module_user::link_open($_SESSION['_user_id'],true);?> <span class="sep">|</span>
                <a href="<?php echo _BASE_HREF;?>index.php?_logout=true"><?php _e('Logout');?></a>
                <div class="date"><?php echo _l('%s %s%s of %s %s',_l(date('l')),date('j'),_l(date('S')),_l(date('F')),date('Y')); ?></div>
			</div>
		<?php
		}
		?>

	</div>

	<div id="main_menu">
        <?php
        $menu_include_parent=false;
        $show_quick_search=true;
        if(is_file('design_menu.php')){
            //include("design_menu.php");
            include(module_theme::include_ucm("design_menu.php"));
        }
        ?>
	</div>

	<div id="page_middle">
    <?php }
    ?>

		<div class="content">

                
        <?php
}