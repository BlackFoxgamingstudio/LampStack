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

define('_THEME_CONFIG_PREFIX','_theme_');

class module_theme extends module_base{
	
	var $links;

    public static $current_theme = '';
    public static $current_theme_settings = array();
    public static $current_theme_styles = array();

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function module_theme(){
		// here because init() doesn't run on install.
        hook_add('plugins_loaded','module_theme::hook_plugins_loaded');
	}
	function init(){
        $this->version = 2.383;
		// 2.383 - 2015-12-27 - fontawesome update
		// 2.382 - 2015-06-07 - new extra field settings button
        // 2.381 - 2015-05-03 - responsive improvements
        // 2.38 - 2015-03-15 - table manager update
        // 2.379 - 2015-03-14 - new help system
        // 2.378 - 2015-01-28 - table sorting by extra fields
        // 2.377 - 2015-01-14 - hooks added to table print out and data processing
        // 2.376 - 2014-12-17 - signup form on login
        // 2.375 - 2014-11-26 - improved form framework
        // 2.374 - 2014-11-05 - welcome_message_role_X template support
        // 2.373 - 2014-08-14 - dashboard widget permission fix
        // 2.372 - 2014-08-12 - new theme support
        // 2.371 - 2014-08-10 - table layout improvements
        // 2.37 - 2014-08-10 - table layout improvements
        // 2.369 - 2014-07-31 - working on better responsive layout
        // 2.368 - 2014-07-31 - menu generation bug fix
        // 2.367 - 2014-07-28 - menu generation speed improvement
        // 2.366 - 2014-07-21 - whitelabel home page fix
        // 2.365 - 2014-07-14 - better theme css caching
        // 2.364 - 2014-07-14 - better theme css caching
        // 2.363 - 2014-07-13 - better theme css caching
        // 2.362 - 2014-07-05 - theme translation fix
        // 2.361 - 2013-11-20 - metis bootstrap/ui button js fix
        // 2.36 - 2013-11-15 - working on new bootstrap UI interface - WOW!
        // 2.35 - 2013-10-01 - mobile menu fix up
        // 2.349 - 2013-08-28 - more work on upcoming new themes
        // 2.348 - 2013-06-18 - easy custom CSS option added
        // 2.347 - 2013-05-27 - dashboard improvements.

        // 2.2 - handling including of files.
        // 2.3 - new pro dark theme beginnings
        // 2.31 - theme selector on settings page
        // 2.32 - change loction of mobile files and custom files.
        // 2.33 - demo theme support
        // 2.34 - left menu theme.
        // 2.341 - left menu update.
        // 2.342 - foreach() bug fix
        // 2.343 - permissoin fix
        // 2.344 - php5/6 fix
        // 2.345 - mobile layout fixes
        // 2.346 - css fixes


		$this->links = array();
		$this->module_name = "theme";
		$this->module_position = 8882;

        if(file_exists('includes/plugin_theme/pages/theme_settings.php') && module_security::has_feature_access(array(
                'name' => 'Settings',
                'module' => 'config',
                'category' => 'Config',
                'view' => 1,
                'description' => 'view',
        ))){
            $this->links[] = array(
                "name"=>"Theme",
                "p"=>"theme_settings",
                'args'=>array(),
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
                'menu_include_parent' => 0,
            );
        }

        // todo - allow themes to override this hook, eg: hook_remove('layout_column_half','module_theme::hook_handle_layout_column');
        hook_add('layout_column_half','module_theme::hook_handle_layout_column');
        hook_add('layout_column_thirds','module_theme::hook_handle_layout_column');

	}

    public static function get_current_theme(){
        if(_DEMO_MODE && get_display_mode()!='mobile' && is_dir('includes/plugin_theme/themes/pro/')){
            hook_add('header_print_js','module_theme::hook_header_print_js_demo');
            if(isset($_REQUEST['demo_theme'])){
                $_SESSION['_demo_theme'] = basename($_REQUEST['demo_theme']);
                if(!$_SESSION['_demo_theme'])$_SESSION['_demo_theme'] = module_config::c('theme_name','default');
            }
            $current_theme = isset($_SESSION['_demo_theme']) ? $_SESSION['_demo_theme'] : module_config::c('theme_name','default');
        }else{
            $current_theme = module_config::c('theme_name',is_dir('includes/plugin_theme/themes/metis/') ? 'metis' : 'default');
            if(module_security::is_logged_in() && module_config::c('theme_per_user',0)){
                // we allow users to pick their own themes.
                $current_theme = module_config::c('theme_name_'.module_security::get_loggedin_id(),self::$current_theme);
            }
        }
        return $current_theme;
    }
    public static function hook_plugins_loaded(){

        $available_themes = self::get_available_themes();
        self::$current_theme = self::get_current_theme();
        $current_theme = basename(self::$current_theme);
        if(strlen($current_theme)>2 && isset($available_themes[$current_theme])){
            // we have an active theme!
            self::$current_theme_settings = $available_themes[$current_theme];
            $file = isset(self::$current_theme_settings['init_file']) ? self::$current_theme_settings['init_file'] : false;
            if($file && is_file($file)){
                include($file);
            }
        }

        $display_mode = get_display_mode();
        if($display_mode!='mobile'){
            module_theme::$current_theme_styles = module_theme::get_theme_styles(module_theme::$current_theme);
            module_config::register_css('theme','theme.php',full_link(_EXTERNAL_TUNNEL_REWRITE.'m.theme/h.css?uniq='.md5(serialize(array(self::$current_theme_settings,module_theme::$current_theme_styles)))),100);
        }
        if($display_mode=='iframe'){
            module_config::register_css('theme','iframe.css',true,100);
        }
    }

    public static function get_available_themes(){

        $themes = array(
            'default' => array(
                'id' => 'default',
                'name' => _l('Default Theme'),
            ),
        );
        $theme_folders = glob('includes/plugin_theme/themes/*');
        if(is_array($theme_folders)){
            foreach($theme_folders as $foo){
                if(is_dir($foo) && !is_file($foo.'/ucm_ignore')){
                    $themes[basename($foo)] = array(
                        'id' => basename($foo),
                        'name' => _l(ucwords(str_replace('_',' ',basename($foo)))),
                        'base_dir' => rtrim($foo,'/').'/',
                        'init_file' => 'includes/plugin_theme/themes/'.basename($foo).'/init.php',
                    );
                }
            }
        }

        // now hook into get any other themes.
        $result = hook_handle_callback('get_themes');
        if(is_array($result)){
            // has been handed by a theme.
            foreach($result as $r){
                if(isset($r['id'])){
                    $themes[$r['id']] = $r;
                }
            }
        }


        return $themes;
    }

    public static function hook_handle_layout_column($column_type,$column_option,$column_width_percent=false){
        switch($column_type){
            case 'layout_column_half':
                if(!$column_width_percent)$column_width_percent=50;
                switch($column_option){
                    case 1:
                        echo '<table class="tableclass_full" cellpadding="10" width="100%"><tbody><tr><td width="'.$column_width_percent.'%" valign="top">';
                        break;
                    case 2:
                        echo '</td><td valign="top">';
                        break;
                    case 'end':
                        echo '</tr></tbody></table>';
                        break;
                }
                break;
            case 'layout_column_thirds':
                if(!$column_width_percent)$column_width_percent=33;
                switch($column_option){
                    case 'start':
                        echo '<table class="tableclass tableclass_full"><tbody><tr>';
                        break;
                    case 'col_start':
                        echo '<td width="'.$column_width_percent.'%" valign="top">';
                        break;
                    case 'col_end':
                        echo '</td>';
                        break;
                    case 'end':
                        echo '</tr></tbody></table>';
                        break;
                }
                break;
        }
    }

    /* this has been moved out of the global functions.php file so themes can style it if they want. */
    public static function print_heading($options){

        // not sure the best way to do this, do hooks for now
        $result = hook_handle_callback('print_heading',$options);
        if($result){
            // has been handed by a theme.
            return;
        }

        if(!is_array($options)){
            $options = array(
                'type' => 'h2',
                'title' => $options,
            );
        }
        $buttons = array();
        if(isset($options['button']) && is_array($options['button']) && count($options['button'])){
            $buttons = $options['button'];
            if(isset($buttons['url'])){
                $buttons = array($buttons);
            }
        }
        if(!isset($options['type'])){
            $options['type'] = 'h2';
        }
        ?>
        <<?php echo $options['type'];?>>
            <?php foreach($buttons as $button){ ?>
            <span class="button">
                <a href="<?php echo $button['url'];?>" class="uibutton<?php echo isset($button['class'])?' '.$button['class']:'';?>"<?php if(isset($button['id'])) echo ' id="'.$button['id'].'"';?><?php if(isset($button['onclick'])) echo ' onclick="'.$button['onclick'].'"';?>>
                    <?php if(isset($button['type']) && $button['type'] == 'add'){ ?> <img src="<?php echo _BASE_HREF;?>images/add.png" width="10" height="10" alt="add" border="0" /> <?php } ?>
                    <span><?php echo _l($button['title']);?></span>
                </a>
            </span>
            <?php } ?>
            <?php if(isset($options['help'])){ ?>
                <span class="button">
                    <?php _h($options['help']);?>
                </span>
            <?php } ?>
            <span class="title">
                <?php echo isset($options['title_final']) ? $options['title_final'] : _l($options['title']);?>
            </span>
        </<?php echo $options['type'];?>>
        <?php
    }

    public function external_hook($hook){
        switch($hook){
            case 'css':
                @ob_end_clean();
                $cache_length = 10800; // 3 hours
                header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_length ) . ' GMT' );
                header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
                header("Pragma: cache");
                header("Cache-Control: max-age=$cache_length");
                header("User-Cache-Control: max-age=$cache_length");
                header('Content-type: text/css');
                $styles = !empty(module_theme::$current_theme_styles) ? module_theme::$current_theme_styles : module_theme::get_theme_styles(module_theme::$current_theme);
                ?>

/** css stylesheet */

<?php foreach($styles as $style){

    if(isset($style['v']) && is_array($style['v'])){
        echo $style['r'].'{';
        foreach($style['v'] as $s=>$v){
            echo $s.':'.$v[0].'; ';
        }
        echo "}\n";
    }

}
                // custom css output (only not in demo mode)
                if(!_DEMO_MODE){
                    echo module_config::c(_THEME_CONFIG_PREFIX.'theme_custom_css');
                }else{
                    echo '/*custom css disabled in demo mode*/';
                }
                exit;
        }
    }
	private static $_header_demo_js_done = false;
    public static function hook_header_print_js_demo(){
	    if(self::$_header_demo_js_done)return;
	    self::$_header_demo_js_done=true;
        $u = preg_replace('#.demo_theme=\w+#','',$_SERVER['REQUEST_URI']);
        ?>
    <script type="text/javascript">
        $(function(){
            $('#profile_info').append('<div id="demo_theme" style="position:absolute; top:0; margin-left: -223px; color:#000; background: #FFF; border-right:1px solid #CCC; border-left:1px solid #CCC; border-bottom:1px solid #CCC; border-bottom-left-radius:5px; border-bottom-right-radius:5px; opacity: 0.5; padding:5px; text-align: center;">' +
                'Change <a href="http://codecanyon.net/item/ultimate-client-manager-crm-pro-edition/2621629?ref=dtbaker&utm_source=Demo&utm_medium=Header&utm_campaign=DemoClicks&utm_content=<?php echo htmlspecialchars(self::$current_theme);?>" target="_blank" style="color:#000;">UCM Pro</a> Theme: ' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=pro" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'pro' ? '000':'FFF';?>; padding:0px 6px; background: #555; color:#FFF; margin:0 3px;" title="UCM Pro - Dark Theme Preview">1</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=blue" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'blue' ? '000':'FFF';?>; padding:0px 6px; background: #DBEFF5; color:#0079C2; margin:0 3px;" title="UCM Pro - Blue Theme Preview">2</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=left" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'left' ? '000':'FFF';?>; padding:0px 6px; background: #555; color:#FFF; margin:0 3px;" title="UCM Pro - Left Menu Theme Preview">3</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=default" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'default' ? '000':'FFF';?>; padding:0px 6px; background: #A7A5A5; color:#FFF; margin:0 3px;" title="UCM Pro - Default Theme Preview">4</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=whitelabel1" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'whitelabel1' ? '000':'FFF';?>; padding:0px 6px; background: #f3f3f3; color:#95cd00; margin:0 3px;" title="UCM Pro - White Label Theme Preview">5</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=metis" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'metis' ? '000':'FFF';?>; padding:0px 6px; background: #333; color:#FFF; margin:0 3px;" title="UCM Pro - Metis Theme Preview">6</a>' +
                '<a href="<?php echo htmlspecialchars($u); echo strpos($u,'?')===false ? '?' : '&'; ?>demo_theme=theme_adminlte" style="display:inline-block; border:2px solid #<?php echo self::$current_theme == 'theme_adminlte' ? '000':'FFF';?>; padding:0px 6px; background: #333; color:#FFF; margin:0 3px;" title="UCM Pro - AdminLTE Theme Preview">7</a>' +
                '<br/>' +
                'Like this software? <a href="http://codecanyon.net/item/ultimate-client-manager-crm-pro-edition/2621629?ref=dtbaker&utm_source=Demo&utm_medium=Header&utm_campaign=DemoClicks&utm_content=<?php echo htmlspecialchars(self::$current_theme);?>" title="Download Ultimate Client Manager Pro Edition" target="_blank" style="color:#000; text-decoration: underline;">Click here</a> to get it!' +
                '</div>');
            $('#demo_theme').hover(function(){
                $(this).stop().animate({"opacity": 1});
            },function(){
                $(this).stop().animate({"opacity": 0.5});
            });
        });
    </script>
    <?php
    }

    public static function include_ucm($include_page){
        // what folder do we search for?
        // custom/includes/plugin_mobile/custom_layout/theme/$theme_name/$page
        // custom/includes/plugin_mobile/custom_layout/$page
        // custom/theme/$theme_name/$page
        // custom/$page
        // includes/plugin_mobile/custom_layout/theme/$theme_name/$page
        // includes/plugin_mobile/custom_layout/$page
        // theme/$theme_name/$page
        // $page

        // sanatise $page.


        $display_mode = get_display_mode();


        $check_files = array();
        $current_theme = basename(self::$current_theme);
        if(strlen($current_theme)>2 && is_dir('includes/plugin_theme/themes/'.$current_theme.'/')){
            // we have an active theme!
        }else{
            $current_theme = false;
        }

        // build up our file listing.
        if($display_mode == 'mobile'){
            $check_files[] = 'custom/'.dirname($include_page).'/mobile/'.basename($include_page);
        }
        $check_files[] = 'custom/'.$include_page;
        if($display_mode == 'mobile'){
            //$check_files[] = 'includes/plugin_mobile/custom_layout/'.$page;
            $check_files[] = dirname($include_page).'/mobile/'.basename($include_page);
        }

        if(self::$current_theme_settings && isset(self::$current_theme_settings['base_dir']) && strlen(self::$current_theme_settings['base_dir']) > 2 && is_dir(self::$current_theme_settings['base_dir'])){
            // we have an active theme!
            $check_files[] = self::$current_theme_settings['base_dir'].$include_page;
        }
        $check_files[] = $include_page;



        foreach($check_files as $file){
            module_debug::log(array(
                'title' => 'IncludeUCM',
                'file' => 'includes/plugin_theme/theme.php',
                'data' => "Checking for include file: ".$file,
            ));
            if(is_file($file)){
                module_debug::log(array(
                    'title' => 'IncludeUCM',
                    'file' => 'includes/plugin_theme/theme.php',
                    'data' => "FOUND FILE! ".$file,
                ));
                return $file;
            }
        }


        module_debug::log(array(
            'title' => 'IncludeUCM',
            'file' => 'includes/plugin_theme/theme.php',
            'data' => "Warning: File not found ".$include_page,
        ));
        return $include_page; // as a defult, wont ever get here.
    }

    public static function get_theme_styles($theme='default'){
        // return an array of the css styles to display on the page, pretty simple.

        $styles = array();


        $styles ['body'] = array(
            'd' => 'Overall page settings',
            'v'=>array(
                'background-color' => '#E7E7E7',
                'background-image' => 'none',
		        'font-family' => 'Arial, Helvetica, sans-serif',
		        'font-size' => '12px',
            ),
        );
        $styles ['body,#profile_info a'] = array(
            'd' => 'Main font color',
            'v'=>array(
                'color' => '#000000',
            ),
        );
        $styles ['#header,#page_middle,#main_menu'] = array(
            'd' => 'Content width',
            'v'=>array(
                'width' => '1294px',
            ),
        );
        $styles ['#header'] = array(
            'd' => 'Header height',
            'v'=>array(
                'height' => '76px',
            ),
        );
        $styles ['#header_logo'] = array(
            'd' => 'Logo padding',
            'v'=>array(
                'padding' => '10px 0 0 12px',
            ),
        );
        $styles ['.nav>ul>li>a,#quick_search_box'] = array(
            'd' => 'Menu items',
            'v'=>array(
                'color' => '#FFFFFF',
                'background-color' => '#A7A5A5',
            ),
        );
        $styles ['.nav>ul>li>a:hover'] = array(
            'd' => 'Menu items (when hovering)',
            'v'=>array(
                'color' => '#000000',
                'background-color' => '#FFFFFF',
            ),
        );
        $styles ['#page_middle>.content,.nav>ul>li>a,#page_middle .nav,#quick_search_box'] = array(
            'd' => 'Menu outline color',
            'v'=>array(
                'border-color' => '#CBCBCB',
            ),
        );
        $styles ['h2'] = array(
            'd' => 'Main Page Title',
            'v'=>array(
                'color' => '#333333',
                'background-color' => '#EEEEEE',
                'border' => '1px solid #cbcbcb',
                'font-size' => '19px',
            ),
        );
        $styles ['h3'] = array(
            'd' => 'Sub Page Title',
            'v'=>array(
                'color' => '#666666',
                'background-color' => '#DFDFDF',
                'font-size' => '15px',
            ),
        );


        if(self::$current_theme_settings && isset(self::$current_theme_settings['base_dir']) && strlen(self::$current_theme_settings['base_dir']) > 2 && is_dir(self::$current_theme_settings['base_dir'])){
            $file = self::$current_theme_settings['base_dir'].'style.php';
            if(is_file($file)){
                include($file);
            }
        }

        foreach($styles as $style_id => $style){
            $styles[$style_id]['r'] = $style_id; // backwards compat
            if(isset($style['v']) && is_array($style['v'])){
                foreach($style['v'] as $k=>$v){
                    $styles[$style_id]['v'][$k] = array(self::get_config($theme.$style_id.'_'.$k,$v),$v);
                }
            }
        }

        return $styles;
    }

    public static function get_config($key,$default=''){
        $style = module_config::c(_THEME_CONFIG_PREFIX.$key,false);
        if($style===false)return $default;
        return $style;
    }

    public static function new_table_manager(){
        require_once(module_theme::include_ucm('includes/plugin_theme/class.table_manager.php'));
        // return our new table manager class.
        // but we allow individual themes to provide their own table manager class.
        $result = hook_handle_callback('get_table_manager');
        if(is_array($result) && isset($result[0])){
            return $result[0];
        }
        return new ucm_table_manager();
    }
}
