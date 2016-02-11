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
if(!function_exists('sort_menu_links')){
	function sort_menu_links($a,$b){
		if(isset($a['order']) && isset($b['order'])){
			return $a['order'] > $b['order'];
		}
		return 1;
	}
}
if(!isset($links)){
    $links = array();
}
if(!isset($menu_holder)){
    $menu_holder = (isset($load_page)) ? $load_page : 'main';
}

    switch($menu_holder){
        case false:
        case 'main':
                // login is always there.
                if(!_UCM_INSTALLED){
                    //$links [] = array("name"=>"Setup","url"=>"index.php?p=setup","icon"=>"images/icon_home.gif");
                }else if(!module_security::is_logged_in()){
                    $links [] = array("name"=>"Login","url"=>_BASE_HREF."index.php?p=home","icon"=>"images/icon_home.gif");
                }else{
                    if($display_mode != 'mobile' && !defined('_CUSTOM_UCM_HOMEPAGE')){
                        $home_link = array(
                            "name"=>"Dashboard",
                            "url"=>_BASE_HREF.'index.php?p=home',
                            "icon_name"=>'home',
                            'order' => 0,
                        );
                        $links[] = $home_link;
                    }
                }
            break;
        default:

            break;

    }

    if(!isset($menu_include_parent)){
        $menu_include_parent = false;
    }
    if(!isset($menu_allow_nesting)){
        $menu_allow_nesting = false;
    }
    $menu_type = false;
    // pull in menus from modules
    $current_module_name = (isset($module)) ? $module->module_name : false;
    // cache menu links
    // problems: 'current' set on customer/vendor is cached. sub menu under customer is cached between items.
    /*$cache_key = 'menu_'.module_security::get_loggedin_id().'_'.$current_module_name.'_'.$menu_holder;
    $cache_timeout = module_config::c('cache_menu_time',600);
    if($cache_timeout > 0 && $cached_item = module_cache::get('global',$cache_key)){
        $links = $cached_item;
        foreach($links as $link_id => $link){
            if(isset($link['current'])){
                unset($links[$link_id]['current']);
            }
        }
    }else{*/
        foreach($plugins as $plugin_name => &$plugin){
            $links = array_merge($links,$plugin->get_menu($current_module_name,$menu_holder));
        }
        uasort($links,'sort_menu_links');
        /*if($cache_timeout > 0){
            module_cache::put('global',$cache_key,$links,$cache_timeout);
        }
    }*/

   // echo '<pre>';print_r($load_modules);echo '</pre>';
    if(!isset($current_link))$current_link = false;


    if($current_link === false){
        foreach($links as $link_id => $link){
            if(isset($link['current'])){
                if($link['current']){
                    $current_link = $link_id;
                    break;
                }else{
                    continue;
                }
            }
        }
    }
    // we get out load modules.
    if($current_link === false && isset($menu_modules)){

        //if($menu_module_index == count($menu_modules)){
        foreach($menu_modules as $menu_module_id => $menu_module){
            foreach($links as $link_id => $link){
                // we highlight the current module
                if(isset($link['p']) && isset($link['m'])){
                    if($menu_module == $link['m'] && $link['p'] == $load_pages[$menu_module_id]){
                        $current_link = $link_id;
                        //$menu_module_index--;
                        unset($menu_modules[$menu_module_id]);
                        break 2;
                    }
                }
            }
        }
        //}
            //break;// if there are menu "current" issues then the problem will be here. remove break;


    }

    // highlight home menu or best guess module menu item.

    if($current_link === false){
        foreach($links as $link_id => $link){
            if(isset($link['current'])){
                if($link['current']){
                    $current_link = $link_id;
                }else{
                    continue;
                }
            }
            // we highlight the current module
            if(isset($load_modules) && isset($link['m']) && in_array($link['m'],$load_modules) && !isset($link['force_current_check'])){
                $current_link = $link_id;
                break;// if there are menu "current" issues then the problem will be here. remove break;
            }else if($link['name'] == 'Dashboard' && !$_REQUEST['m'] && isset($_REQUEST['p']) && in_array('home',$_REQUEST['p'])){
                $current_link = $link_id;
            }
        }
    }
    $current_selected_link = false;

    switch($menu_holder){
        case false:
        case 'main':
            //if(isset($header_nav_mini))$header_nav_mini .= '<ul class="nav navbar-nav">';
            ?>
            <ul id="menu" class="collapse affix-top">
                <li class="nav-header"><?php _e('Main Menu');?></li>
                <li class="nav-divider"></li>
                <?php
            break;
        default:

            // find the current link to display in collapsed branding
            $highlighted_link = false;
            $current = false;
            foreach($links as $link_id => $link){
                $current = ($link_id === $current_link);
                if($current){
                    $highlighted_link = $link;
                }
            }
            $menu_id = 'menu-'.md5($menu_holder.$current);
            ?>
            <nav class="sub-menu navbar navbar-default" role="navigation">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#<?php echo $menu_id;?>">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <?php if($highlighted_link){ ?>
                    <a class="navbar-brand visible-xs" href="#">
                    <?php if(isset($highlighted_link['icon_name'])){ ?><i class="fa fa-<?php echo $highlighted_link['icon_name'];?>"></i><?php } ?>
                    <?php echo _l($highlighted_link['name']); ?>
                    </a>
                <?php } ?>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="<?php echo $menu_id;?>">
                <ul class="nav navbar-nav">
                <?php
            break;

    }
    foreach($links as $link_id => $link){
        $current = ($link_id === $current_link);
        if(isset($link['url']) && $link['url']){
            $link_url = $link['url'];
        }else if(isset($link['p']) && $link['m']){
            if(isset($link['menu_include_parent'])){
                $menu_include_parent = $link['menu_include_parent'];
            }
            $link_nest = $menu_allow_nesting;
            if(isset($link['allow_nesting'])){
                $link_nest = $link['allow_nesting'];
            }
            $link_url = generate_link($link['p'],(isset($link['args'])?$link['args']:array()),$link['m'],$menu_include_parent,$link_nest);
        }else{
            $link_url = '#';
        }
        if($current){
            $current_selected_link = $link;
        }
        // reformat out menu labels
//        $link['name'] = str_replace('menu_label','badge',$link['name']);
        $link['name'] = str_replace('menu_label','label label-info',$link['name']);
        $link['name'] = str_replace('important','label-danger',$link['name']);
        // add class panel if having a dropdown
        ob_start();
        ?>
        <li class="<?php if($current){ echo ' active'; } ?>">
            <a href="<?php echo $link_url; ?>"><?php if(isset($link['icon_name'])){ ?><i class="fa fa-<?php echo $link['icon_name'];?>"></i><?php } ?>
                <?php echo _l($link['name']); ?>
            </a>
        </li>
        <?php
        $nav_item = ob_get_clean();
        echo $nav_item; // for the main menu
        if(isset($header_nav_mini))$header_nav_mini .= $nav_item; // for the responsive menu
    }

    if(isset($show_quick_search) && $show_quick_search){
        handle_hook('top_menu_end');
    }

    switch($menu_holder){
        case false:
        case 'main':
            // we don't close off this menu because the theme adds some stuff to it in design_header.php - hacky!
            //if(isset($header_nav_mini))$header_nav_mini .= '</ul>'; // for the responsive menu
            ?>
            </ul>
            <?php
            break;
        default:
            ?>
            </ul>
            </div>
            </nav>
            <?php
            break;

    }

    unset($menu_holder);
    unset($menu_type);
    unset($current_link);
    unset($menu_allow_nesting);

if(!isset($do_metis_inner_div)){
    $do_metis_inner_div = true;
    //<div class="inner">
    ?>

    <?php
}