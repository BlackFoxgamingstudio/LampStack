<?php 

if(!function_exists('thmtheme_setup')):

    function thmtheme_setup()
    {
        //Textdomain
        load_theme_textdomain( 'themeum', get_template_directory() . '/languages' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'featured-ideas', 458, 353, true );  // Home page shortcode
        add_image_size( 'project-thumb', 360, 250, true ); // Project page shortcode
        add_image_size( 'blog-thumb', 690, 347, true ); // blog page shortcode
        add_image_size( 'blog-full', 1140, 500, true );
        add_theme_support( 'post-formats', array( 'aside','audio','gallery','image','link','quote','video' ) );
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
        add_theme_support( 'automatic-feed-links' );

        add_editor_style('');

        if ( ! isset( $content_width ) )
        $content_width = 660;
    }

    add_action('after_setup_theme','thmtheme_setup');

endif;


/*-------------------------------------------*
 *      Themeum Widget Registration
 *------------------------------------------*/

if(!function_exists('thmtheme_widdget_init')):

    function thmtheme_widdget_init()
    {

        register_sidebar(array( 'name'          => __( 'Sidebar', 'themeum' ),
                                'id'            => 'sidebar',
                                'description'   => __( 'Widgets in this area will be shown on Sidebar.', 'themeum' ),
                                'before_title'  => '<h3  class="widget_title">',
                                'after_title'   => '</h3>',
                                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                                'after_widget'  => '</div>'
                    )
        );

        register_sidebar(array( 
                            'name'          => __( 'Bottom', 'themeum' ),
                            'id'            => 'bottom',
                            'description'   => __( 'Widgets in this area will be shown before Footer.' , 'themeum'),
                            'before_title'  => '<h3 class="widget_title">',
                            'after_title'   => '</h3>',
                            'before_widget' => '<div class="col-sm-3"><div id="%1$s" class="widget %2$s" >',
                            'after_widget'  => '</div></div>'
                            )
        );

    }
    
    add_action('widgets_init','thmtheme_widdget_init');

endif;




/*-------------------------------------------*
 *      Themeum Style
 *------------------------------------------*/

if(!function_exists('themeum_style')):

    function themeum_style(){
        global $themeum_options;

        wp_enqueue_style('thm-style',get_stylesheet_uri());
        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap',THMJS.'bootstrap.min.js',array(),false,true);
        wp_enqueue_script('owl.carousel.min',THMJS.'owl.carousel.min.js',array(),false,true);
        wp_enqueue_script('mediaelement-and-player',THMJS.'mediaelement-and-player.min.js',array(),false,true);
        wp_enqueue_script('wow.min',THMJS.'wow.min.js',array(),false,true);
        wp_enqueue_script('countdown',THMJS.'countdown.js',array(),false,true);
        wp_enqueue_script('jquery.countdown',THMJS.'jquery.countdown.min.js',array(),false,true);
        wp_enqueue_script('jquery.prettyPhoto',THMJS.'jquery.prettyPhoto.js',array(),false,true);
        wp_enqueue_script('jquery-blockUI',THMJS.'jquery.blockUI.min.js',array(),false,true);
        wp_enqueue_media();
        wp_enqueue_script('adsScript', get_template_directory_uri() . '/js/image-uploader.js');
        wp_enqueue_style('quick-preset',get_template_directory_uri().'/quick-preset.php',array(),false,'all');
        wp_enqueue_style('quick-style',get_template_directory_uri().'/quick-style.php',array(),false,'all');
        wp_enqueue_script('checkout-stripe','https://checkout.stripe.com/checkout.js',array(),false,true);
        wp_enqueue_script('jquery-validate','http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js',array(),false,true);
        wp_enqueue_script('main',THMJS.'main.js',array(),false,true);
        wp_localize_script('main','paymentAjax',array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'paymentNonce' => wp_create_nonce('payment_form_submit')
            )
        );

    }

    add_action('wp_enqueue_scripts','themeum_style');

endif;

/*-------------------------------------------------------
*           Include the TGM Plugin Activation class
*-------------------------------------------------------*/

require_once( get_template_directory()  . '/lib/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'themeum_plugins_include' );

if(!function_exists('themeum_plugins_include')):

    function themeum_plugins_include()
    {
        $plugins = array(
                array(
                    'name'                  => 'WPBakery Visual Composer',
                    'slug'                  => 'js_composer',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/js_composer.zip',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => true,
                    'force_deactivation'    => true,
                    'external_url'          => '',
                ),
                array(
                    'name'                  => 'Themeum Startup Idea',
                    'slug'                  => 'themeum-startup-idea',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/themeum-startup-idea.zip',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => true,
                    'force_deactivation'    => true,
                    'external_url'          => '',
                ),                  
                array(
                    'name'                  => 'Revolution Slider',
                    'slug'                  => 'revslider',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/revslider.zip',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => true,
                    'force_deactivation'    => true,
                    'external_url'          => '',
                ),              
                array(
                    'name'                  => 'Contact Form 7', // The plugin name
                    'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
                    'required'              => false, // If false, the plugin is only 'recommended' instead of required
                    'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                    'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'external_url'          => 'https://downloads.wordpress.org/plugin/contact-form-7.4.3.zip', // If set, overrides default API URL and points to an external URL
                ),      
                array(
                    'name'                  => 'Widget Settings Importer/Exporter',
                    'slug'                  => 'widget-settings-importexport',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => 'https://downloads.wordpress.org/plugin/widget-settings-importexport.1.5.0.zip',
                ),
                array(
                    'name'                  => 'Meta Box Group',
                    'slug'                  => 'meta-box-group',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/meta-box-group.zip',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => true,
                    'force_deactivation'    => true,
                    'external_url'          => '',
                )
            );
    $config = array(
            'domain'            => 'themeum',           // Text domain - likely want to be the same as your theme.
            'default_path'      => '',                           // Default absolute path to pre-packaged plugins
            'parent_menu_slug'  => 'themes.php',                 // Default parent menu slug
            'parent_url_slug'   => 'themes.php',                 // Default parent URL slug
            'menu'              => 'install-required-plugins',   // Menu slug
            'has_notices'       => true,                         // Show admin notices or not
            'is_automatic'      => false,                        // Automatically activate plugins after installation or not
            'message'           => '',                           // Message to output right before the plugins table
            'strings'           => array(
                        'page_title'                                => __( 'Install Required Plugins', 'themeum' ),
                        'menu_title'                                => __( 'Install Plugins', 'themeum' ),
                        'installing'                                => __( 'Installing Plugin: %s', 'themeum' ), // %1$s = plugin name
                        'oops'                                      => __( 'Something went wrong with the plugin API.', 'themeum'),
                        'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
                        'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
                        'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
                        'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
                        'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
                        'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
                        'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
                        'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
                        'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                        'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
                        'return'                                    => __( 'Return to Required Plugins Installer', 'themeum'),
                        'plugin_activated'                          => __( 'Plugin activated successfully.','themeum'),
                        'complete'                                  => __( 'All plugins installed and activated successfully. %s', 'themeum' ) // %1$s = dashboard link
                )
    );

    tgmpa( $plugins, $config );

    }

endif;