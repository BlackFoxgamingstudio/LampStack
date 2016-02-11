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
    case 'ajax':

        break;
    case 'iframe':
    case 'normal':
    default:

        $header_nav_mini = '';
        ob_start();
        $menu_include_parent=false;
        $show_quick_search=true;
        include(module_theme::include_ucm("design_menu.php"));
        $main_menu = ob_get_clean();

        ?>

        <!DOCTYPE html>
        <html dir="<?php echo module_config::c('text_direction','ltr');?>"  id="html-<?php echo isset($page_unique_id) ? $page_unique_id : 'page';?>">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $page_title; ?></title>

        <?php $header_favicon = module_theme::get_config('theme_favicon','');
            if($header_favicon){ ?>
                <link rel="icon" href="<?php echo htmlspecialchars($header_favicon);?>">
        <?php } ?>


            <link rel="stylesheet" href="<?php echo _BASE_HREF;?>includes/plugin_theme/themes/metis/css/custom-theme/jquery-ui-1.10.3.custom.css">
            <!--[if lt IE 9]>
            <link rel="stylesheet" href="<?php echo _BASE_HREF;?>includes/plugin_theme/themes/metis/css/jquery.ui.1.10.3.ie.css">
            <![endif]-->


        <?php module_config::print_css(_SCRIPT_VERSION);?>

        <script language="javascript" type="text/javascript">
            // by dtbaker.
            var ajax_search_ini = '';
            var ajax_search_xhr = false;
            var ajax_search_url = '<?php echo _BASE_HREF;?>ajax.php';
        </script>



        <script type="text/javascript" src="<?php echo _BASE_HREF;?>includes/plugin_theme/themes/metis/lib/jquery.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>includes/plugin_theme/themes/metis/lib/jquery-ui-1.10.3.custom.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <!-- <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-ui-1.9.2.custom.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/timepicker.js?ver=<?php echo _SCRIPT_VERSION;?>"></script> -->
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/cookie.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/javascript.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <?php module_config::print_js(_SCRIPT_VERSION);?>


        <!--
        Author: David Baker (dtbaker.com.au)
        10/May/2010
        -->
        <script type="text/javascript">
            $(function(){
                // override default button init - since we're using jquery ui here.
                ucm.init_buttons = function(){};
                ucm.init_interface();
                if(typeof ucm.metis != 'undefined'){
                    ucm.metis.init();
                    <?php if(module_theme::get_config('metismenustyle','normal') == 'fixed'){ ?>
                    $('#menu').affix({offset: {top: $('#menu').offset().top}}, 100).css({height: $(window).height()});
                    <?php } ?>
                }
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
        <?php //fixed side-right ?>
        <body class="<?php
        if(module_theme::get_config('metissidebar-position','left') == 'right'){
            echo 'side-right ';
        }else{
            echo 'side-left ';
        }
        if(module_theme::get_config('metispagewidth','wide') == 'narrow'){
            echo 'fixed ';
        }else{
            echo 'wide ';
        }
        ?>"  id="<?php echo isset($page_unique_id) ? $page_unique_id : 'page';?>" <?php if($display_mode=='iframe') echo ' style="background:#FFF;"';?>>

<?php if($display_mode=='iframe'){ ?>

<div id="iframe">

<?php }else{ ?>

    <?php if(_DEBUG_MODE){
        module_debug::print_heading();
    } ?>

<?php if(module_security::getcred()){ ?>
    <header class="navbar navbar-inverse navbar-top visible-xs" role="banner" id="responsive_mini_header">
      <div class="container" id="menu_copy_holder">
        <div class="navbar-header">
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#responsive_mini_header > div > nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
              <?php if($header_logo = module_theme::get_config('theme_logo',_BASE_HREF.'images/logo.png')){ ?>
                <a href="<?php echo _BASE_HREF;?>" class="navbar-mini-brand"><img src="<?php echo htmlspecialchars($header_logo);?>" border="0" title="<?php echo htmlspecialchars(module_config::s('header_title','UCM'));?>"></a>
            <?php }else{ ?>
                <a href="<?php echo _BASE_HREF;?>" class="navbar-mini-brand"><?php echo module_config::s('header_title','UCM');?></a>
            <?php } ?>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav">
                <?php if(module_security::can_user(module_security::get_loggedin_id(),'Show Quick Search')){

                    if(module_config::c('global_search_focus',1)==1){
                        module_form::set_default_field('ajax_search_text');
                    }
                    ?>
                    <li>
                        <form class="mini-search" action="<?php echo _BASE_HREF;?>?p=search_results" method="post">
                            <div class="input-group">
                                <input type="text" class="input-small form-control" value="<?php echo isset($_REQUEST['search']) ? htmlspecialchars($_REQUEST['search']) : '';?>" name="search" placeholder="<?php _e('Quick Search:'); ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </li>
                    <?php
                }
                if(module_security::getcred()){ ?>
                <li>
                    <a href="<?php echo module_user::link_open($_SESSION['_user_id']);?>">
                    <i class="fa fa-user"></i> <?php $user = module_user::get_user(module_security::get_loggedin_id()); _e('Welcome %s',$user['name']); ?>
                    </a>
               </li>
                <?php } ?>
                <?php
                // this special nav menu is generated from header_menu.php to contain items that display only in responsive mini mode
                echo $header_nav_mini;
                // add some custom stuff underneath here
                ?>
                <li>
                    <a href="<?php echo _BASE_HREF;?>index.php?_logout=true"><i class="fa fa-power-off"></i> <?php _e('Logout');?></a>
               </li>
            </ul>
        </nav>

        </div>
    </header>
<?php } ?>

<div id="wrap">

	<div id="top">

        <!-- <div class="header-spacer visible-xs" style="height:51px;"></div> -->
        <nav class="navbar navbar-inverse navbar-static-top hidden-xs">
            <!-- Brand and toggle get grouped for better mobile display -->
            <header class="navbar-header">
                <?php if($header_logo = module_theme::get_config('theme_logo',_BASE_HREF.'images/logo.png')){ ?>
                <a href="<?php echo _BASE_HREF;?>" class="navbar-brand"><img src="<?php echo htmlspecialchars($header_logo);?>" border="0" title="<?php echo htmlspecialchars(module_config::s('header_title','UCM'));?>"></a>
            <?php }else{ ?>
                <a href="<?php echo _BASE_HREF;?>" class="navbar-brand"><?php echo module_config::s('header_title','UCM');?></a>
            <?php } ?>
          </header>

            <?php /*if (module_security::getcred()){ ?>

            <div class="topnav">

                <div class="btn-toolbar">
                    <!-- <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Show / Hide Sidebar" data-toggle="tooltip" class="btn btn-success btn-sm" id="changeSidebarPos">
                            <i class="fa fa-resize-horizontal"></i>
                        </a>
                    </div> -->
                    <!-- <div class="btn-group">
                        <a data-placement="bottom" data-original-title="E-mail" data-toggle="tooltip" class="btn btn-default btn-sm">
                            <i class="fa fa-envelope"></i>
                            <span class="label label-warning">5</span>
                        </a>
                        <a data-placement="bottom" data-original-title="Messages" href="#" data-toggle="tooltip" class="btn btn-default btn-sm">
                            <i class="fa fa-comments"></i>
                            <span class="label label-danger">4</span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Document" href="#" data-toggle="tooltip" class="btn btn-default btn-sm">
                            <i class="fa fa-file"></i>
                        </a>
                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#helpModal">
                            <i class="fa fa-question"></i>
                        </a>
                    </div> -->
                    <div class="btn-group">
                        <a href="<?php echo _BASE_HREF;?>index.php?_logout=true" data-toggle="tooltip" data-original-title="<?php _e('Logout');?>" data-placement="bottom" class="btn btn-metis-1 btn-sm">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </div>
                </div>
            </div>

            <?php }*/ ?>

            <?php if(_DEMO_MODE){ ?>
            <div id="profile_info" style="font-size: 11px;float:right;"></div>
            <?php } ?>

        </nav>
        <!-- /.navbar -->

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

         <!-- header.head -->
        <header class="head">
            <div class="search-bar hidden-xs">
                <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
                    <i class="fa fa-resize-full"></i>
                </a>

                <?php if(module_security::getcred() && module_security::can_user(module_security::get_loggedin_id(),'Show Quick Search')){

                    if(module_config::c('global_search_focus',1)==1){
                        module_form::set_default_field('ajax_search_text');
                    }
                    ?>
                    <form class="main-search" action="<?php echo _BASE_HREF;?>?p=search_results" method="post">
                        <div class="input-group">

                            <input type="text" class="input-small form-control" name="quicksearch" value="<?php echo isset($_REQUEST['quicksearch']) ? htmlspecialchars($_REQUEST['quicksearch']) : '';?>" placeholder="<?php _e('Quick Search:'); ?>">
                            <div id="ajax_search_result"></div>
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <?php
                } ?>
            </div>
            <!-- ."main-bar -->
            <div class="main-bar">
                <h3>
                <?php
                //<i class="fa fa-home"></i> {page title here}
                echo isset($GLOBALS['metis_main_title']) ? $GLOBALS['metis_main_title'] : $page_title;
                ?>
                </h3>
            </div>
            <!-- /.main-bar -->
        </header>
        <!-- end header.head -->


    </div>
    <!-- /#top -->

    <?php if(module_security::getcred()){ ?>
    <div id="left">

        <div class="media user-media">
            <div class="media-body">
                <h5 class="media-heading"><?php $user = module_user::get_user(module_security::get_loggedin_id()); _e('Welcome %s',htmlspecialchars($user['name'])); ?></h5>
                <ul class="list-unstyled user-info">
                    <li><small><i class="fa fa-user"></i> <a href="<?php echo module_user::link_open($_SESSION['_user_id']);?>"><?php _e('Edit Profile');?></a></small></li>
	                <?php
	                $header_buttons = array();
			    if(module_security::is_logged_in()) {
				    $header_buttons = hook_filter_var( 'header_buttons', $header_buttons );
			    }
			    foreach($header_buttons as $header_button){
				    ?>
			        <li>
				        <small><i class="fa fa-<?php echo $header_button['fa-icon'];?>"></i>
				        <a href="#" id="<?php echo $header_button['id'];?>">
					        <?php echo $header_button['title'];?>
					    </a>
				        </small>
			        </li>
			    <?php
			    }
			    ?>
                    <li><small><i class="fa fa-calendar"></i> <?php echo _l('%s %s%s of %s %s',_l(date('D')),date('j'),_l(date('S')),_l(date('F')),date('Y')); ?></small></li>
                    <li><small><i class="fa fa-power-off"></i><a href="<?php echo _BASE_HREF;?>index.php?_logout=true"> <?php _e('Logout');?></a></small></li>
                </ul>
            </div>
        </div>

        <?php
        // this is generated from header_menu.php at the top of this file.
        echo $main_menu;
        ?>

    </div>
    <!-- /#left -->
<?php } // logged in ?>

<?php } // iframe ?>

    <div id="content">
        <div class="outer">
            <?php // <div class="inner"> moved to design_menu in a hack to get it looking better ?>
            <div class="inner">

        <?php
}

ob_start();