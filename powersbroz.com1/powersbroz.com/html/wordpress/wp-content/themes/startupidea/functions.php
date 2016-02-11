<?php
define('THEMEUMNAME', wp_get_theme()->get( 'Name' ));
define('THMCSS', get_template_directory_uri().'/css/');
define('THMJS', get_template_directory_uri().'/js/');


// Re-define meta box path and URL
if ( ! defined( 'RWMB_URL' ) )
	define( 'RWMB_URL', trailingslashit( get_template_directory_uri() . '/lib/meta-box' ) );
if ( ! defined( 'RWMB_DIR' ) )
	define( 'RWMB_DIR', trailingslashit(  get_template_directory() . '/lib/meta-box' ) );

// Include the meta box script
require_once RWMB_DIR . 'meta-box.php';
require_once (get_template_directory().'/lib/metabox.php');

/*-------------------------------------------------------
 *				Redux Framework Options Added
 *-------------------------------------------------------*/

global $themeum_options; 

if ( !class_exists( 'ReduxFramework' ) ) {
	require_once( get_template_directory() . '/admin/framework.php' );
}

if ( !isset( $redux_demo ) ) {
	require_once( get_template_directory() . '/theme-options/admin-config.php' );
}

/*-------------------------------------------------------
 *				Login and Register
 *-------------------------------------------------------*/
require get_template_directory() . '/lib/registration.php';
require get_template_directory() . '/lib/login.php';


/*-------------------------------------------*
 *				Register Navigation
 *------------------------------------------*/
register_nav_menus( array(
	'primary' => 'Primary Menu'
) );


/*-------------------------------------------*
 *				title tag
 *------------------------------------------*/
add_theme_support( 'title-tag' );

/*-------------------------------------------*
 *				navwalker
 *------------------------------------------*/
//Main Navigation
require_once( get_template_directory()  . '/lib/menu/admin-megamenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/meagmenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/mobile-navwalker.php');
//Admin mega menu
add_filter( 'wp_edit_nav_menu_walker', function( $class, $menu_id ){
	return 'Themeum_Megamenu_Walker';
}, 10, 2 );



/*-------------------------------------------*
 *				Startup Register
 *------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/startup-register.php');

require_once( get_template_directory()  . '/lib/main-function/themeum-dashboard.php');



/*-------------------------------------------------------
 *			Themeum Request
 *-------------------------------------------------------*/
require_once( get_template_directory()  . '/account/request.php');



/*-------------------------------------------------------
 *			Themeum Core
 *-------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/themeum-core.php');

/*--------------------------------------------------------------
 * 		Project (Add New Project)
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/project-helper.php');

/*--------------------------------------------------------------
 * 		Project (Add New Project)
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/project-submit.php');

/*--------------------------------------------------------------
 * 		Project (Update New Project)
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/project-update.php');

/*--------------------------------------------------------------
 * 					Personal Profile Data
 *-------------------------------------------------------------*/	
require_once( get_template_directory()  . '/lib/main-function/personal-data.php');

/*--------------------------------------------------------------
 * 					AJAX login System
 *-------------------------------------------------------------*/	
require_once( get_template_directory()  . '/lib/main-function/ajax-login.php');


/*--------------------------------------------------------------
 * 	Theme Activation Hook (create login and registration page)
 *-------------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/login-registration.php');

/*-------------------------------------------------------
*			Custom Widgets and VC shortocde Include
*-------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/widgets/image_widget.php');
require_once( get_template_directory()  . '/lib/widgets/blog-posts.php');
require_once( get_template_directory()  . '/lib/widgets/follow_us_widget.php');
require_once( get_template_directory()  . '/lib/vc-addons/fontawesome-helper.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-action.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-address.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-button.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-feature-box.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-icons.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-image-caption.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-person.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-social-media.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-feature-items.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-heading.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-featured-title.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-partners.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-recent-blog.php');
require_once( get_template_directory()  . '/lib/vc-addons/themeum-video-popup.php');


/*-------------------------------------------------------
*			Custom Message Box Generator
*-------------------------------------------------------*/

function themeum_message_generator($id,$header,$body){
	$output = '';
		$output .= '<div id="'.$id.'-msg" class="startup-modal modal fade">';
		    $output .= '<div class="modal-dialog modal-md">';
		        $output .= '<div class="modal-content">';
		            $output .= '<div class="modal-header">';
		                $output .= '<button type="button" id="'.$id.'-close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		                $output .= '<h4 class="modal-title">'.$header.'</h4>';
		            $output .= '</div>';
		             $output .= '<div class="modal-body text-center">';
		                 $output .= '<p>'.$body.'</p>';
		             $output .= '</div>';
		         $output .= '</div>';
		    $output .= '</div>'; 
		$output .= '</div>';
	return $output;
}

function themeum_get_post_author_id(){
    $post_id = $_GET['project_id'];
    $post = get_post( $post_id );
    if(isset($post->post_author)){
        return $post->post_author;
    }else{
        return 'abc';
    }
}

function themeum_my_project_id_list($id){
    global $wpdb;
    $args = array(
    'post_type' 	=> 'project',
    'author'        =>  $id,
    'post_status'	=> 'publish',
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page'	=> -1
    );
    $results_id = '';

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
			 $results_id[] = $post->ID;
	endforeach;
    if(!is_array($results_id)){ $results_id[] = 123343434; }
	wp_reset_postdata();

    return $results_id;
}