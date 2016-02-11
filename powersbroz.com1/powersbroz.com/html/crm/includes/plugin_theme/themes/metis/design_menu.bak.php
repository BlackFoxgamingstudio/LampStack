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
                            "icon"=>"images/icon_home.gif",
                            'order' => 0,
                        );
                        if(!isset($_REQUEST['m']) || !$_REQUEST['m'] || $_REQUEST['m'][0] == 'index'){
                            $home_link['current'] = true;
                        }
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
    foreach($plugins as $plugin_name => &$plugin){
        $links = array_merge($links,$plugin->get_menu($current_module_name,$menu_holder));

    }
    uasort($links,'sort_menu_links');

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
            ?>
            <ul id="menu" class="collapse">
                <li class="nav-header"><?php _e('Main Menu');?></li>
                <li class="nav-divider"></li>
                <?php
            break;
        default:
            ?>
            <ul class="nav nav-tabs">
                <?php
            break;

    }

    foreach($links as $link_id => &$link){
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
        $link['name'] = str_replace('menu_label','label',$link['name']);
        // add class panel if having a dropdown
        ?>
        <li class="<?php if($current){ echo ' active'; } ?>">
            <!-- <li><a href="table.html"><i class="fa fa-table"></i> Tables</a></li> -->
            <a href="<?php echo $link_url; ?>"><?php if(isset($link['icon_name'])){ ?><i class="fa fa-<?php echo $link['icon_name'];?>"></i><?php } ?>
                <?php echo _l($link['name']); ?>
            </a>
        </li>
    <?php }
    unset($menu_holder);
    unset($menu_type);
    unset($current_link);
    unset($menu_allow_nesting);
    ?>
    <?php if(isset($show_quick_search) && $show_quick_search){
        handle_hook('top_menu_end');
    } ?>
</ul>

<?php


if(!isset($do_metis_inner_div)){
    $do_metis_inner_div = true;
    //<div class="inner">
    ?>

    <?php
}