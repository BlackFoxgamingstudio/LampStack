<?php
namespace feedthemsocial;
class feed_them_social_functions {
	public $output = "";
	function __construct() {
		$root_file = plugin_dir_path(dirname( __FILE__));
		$this->premium = str_replace('feed-them-social/', 'feed-them-premium/', $root_file);
		$this->facebook_reviews = str_replace('feed-them-social/', 'feed-them-social-facebook-reviews/', $root_file);
		
		//FTS Activation Function. Commenting out for future use. SRL
		// register_activation_hook( FEED_THEM_MAIN_FILE , array( $this, 'fts_plugin_activation'));
		
		//$load_fts->fts_get_check_plugin_version('feed-them-premium.php', '1.3.0');
		register_deactivation_hook( __FILE__, array( $this, 'fts_get_check_plugin_version' ));
		// Widget Code
		add_filter('widget_text', 'do_shortcode');
		// This is for the fts_clear_cache_ajax submission
	 add_action( 'init', array( $this, 'fts_clear_cache_script'));
		add_action( 'wp_head', array($this, 'my_fts_ajaxurl'));
		add_action( 'wp_ajax_fts_clear_cache_ajax', array($this, 'fts_clear_cache_ajax'));
		// If Premium is actuive
		
		if (is_plugin_active('feed-them-premium/feed-them-premium.php') || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') || is_plugin_active('fts-bar/fts-bar.php')) {
			// Load More Options
		//	add_action( 'init', array($this, 'my_fts_fb_script_enqueuer')); 
			add_action( 'wp_ajax_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more'));
			add_action( 'wp_ajax_nopriv_my_fts_fb_load_more', array($this, 'my_fts_fb_load_more'));
		}//END if premium
		// This is for the Twitter videos, when you click show media
		add_action( 'init', array($this, 'fts_load_videos_script'));
		add_action( 'wp_ajax_fts_load_videos_ajax', array($this, 'fts_load_videos_ajax'));
		add_action( 'wp_ajax_fts_load_videos', array($this, 'fts_load_videos'));
		add_action( 'wp_ajax_nopriv_fts_load_videos', array($this, 'fts_load_videos'));
	
		
	}
	//**************************************************
	// Add FTS options on activation. Commenting out for future use. SRL
	//**************************************************
	// function fts_plugin_activation() {
		   //Options List
	//	   $activation_options = array(
	//		   'fb_language' => 'en_US',
	//	   );
	//	   foreach($activation_options as $option_key => $option_value){
	//		   add_option($option_key, $option_value);
	//	   }   
	//	}
	
	
	//**************************************************
	// For Loading in the Admin.
	//**************************************************
	function init() {
		if ( is_admin() ) {
			// Register Settings
			add_action('admin_init', array($this, 'fts_settings_page_register_settings' ));
			add_action('admin_init', array($this, 'fts_facebook_style_options_page' ));
			add_action('admin_init', array($this, 'fts_twitter_style_options_page' ));
			add_action('admin_init', array($this, 'fts_instagram_style_options_page' ));
			add_action('admin_init', array($this, 'fts_pinterest_style_options_page' ));
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				add_action('admin_init', array($this, 'fts_youtube_style_options_page' ));
			}
			// Adds setting page to FTS menu
			add_action('admin_menu', array($this, 'Feed_Them_Main_Menu'));
			add_action('admin_menu', array($this, 'Feed_Them_Submenu_Pages'));
			// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
			add_action('admin_enqueue_scripts', array($this, 'feed_them_admin_css'));
			//Main Settings Page
			if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page' or isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-instagram-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-pinterest-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-youtube-feed-styles-submenu-page') {
				add_action('admin_enqueue_scripts',  array( $this, 'feed_them_settings'));
			}
			//System Info Page
			if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page') {
				add_action('admin_enqueue_scripts', array( $this, 'feed_them_system_info_css'));
			}
		}//end if admin
		//FTS Admin Bar
		add_action('wp_before_admin_bar_render', array( $this, 'fts_admin_bar_menu'), 999);
		//Settings option. Add Custom CSS to the header of FTS pages only
		$fts_include_custom_css_checked_css =  get_option( 'fts-color-options-settings-custom-css' );
		if ($fts_include_custom_css_checked_css == '1') {
			add_action('wp_enqueue_scripts', array( $this, 'fts_color_options_head_css'));
		}
		//Facebook Settings option. Add Custom CSS to the header of FTS pages only
		$fts_include_fb_custom_css_checked_css =  '1'; //get_option( 'fts-color-options-settings-custom-css' );
		if ($fts_include_fb_custom_css_checked_css == '1') {
			add_action('wp_enqueue_scripts', array( $this, 'fts_fb_color_options_head_css'));
		}
		//Settings option. Custom Powered by Feed Them Social Option
		$fts_powered_text_options_settings =  get_option( 'fts-powered-text-options-settings' );
		if ($fts_powered_text_options_settings != '1') {
			add_action('wp_enqueue_scripts', array( $this, 'fts_powered_by_js'));
		}
	}//end if init
	//**************************************************
	// ajax var on front end for twitter videos and loadmore button (if premium active)
	function my_fts_ajaxurl() {
			wp_enqueue_script( 'jquery' ); ?>
<script type="text/javascript">
var myAjaxFTS = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
	}
	//**************************************************
	// enqueue and localise scripts
	// THE AJAX ADD ACTIONS
	// this function is being called from the fb feed... it calls the ajax in this case.
	//**************************************************
	function my_fts_fb_load_more() {
		if ( !wp_verify_nonce( $_REQUEST['fts_security'], $_REQUEST['fts_time'].'load-more-nonce')) {
			exit( 'Sorry, You can\'t do that!' );
		}
		else {
			if (preg_match('/\[fts_facebook/', $_REQUEST['rebuilt_shortcode']) || preg_match('/\[fts_facebookbiz/', $_REQUEST['rebuilt_shortcode']) || preg_match('/\[fts_instagram/', $_REQUEST['rebuilt_shortcode'])) {
				$object = do_shortcode($_REQUEST['rebuilt_shortcode']);
				echo $object;
			}
			else {
				exit( 'That is not an FTS shortcode!' );
			}
		}
		die();
	}
	//**************************************************
	// This is for the fts_clear_cache_ajax submission
	//**************************************************
	function fts_clear_cache_script() {
		isset($ftsDevModeCache) ? $ftsDevModeCache : "";
		isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
		$ftsAdminBarMenu = get_option('fts_admin_bar_menu');
		$ftsDevModeCache = get_option('fts_clear_cache_developer_mode');
		if ($ftsDevModeCache == '1') {
			wp_enqueue_script( 'fts_clear_cache_script', WP_PLUGIN_URL .'/feed-them-social/admin/js/developer-admin.js', array( 'jquery' ) );
			wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'fts_clear_cache_script' );
		}
		if (!$ftsDevModeCache == 'hide-admin-bar-menu' && !$ftsDevModeCache == '1') {
			wp_enqueue_script( 'fts_clear_cache_script', WP_PLUGIN_URL .'/feed-them-social/admin/js/admin.js', array( 'jquery' ) );
			wp_enqueue_script( 'fts_clear_cache_script', WP_PLUGIN_URL .'/feed-them-social/admin/js/developer-admin.js', array( 'jquery' ) );
			wp_localize_script( 'fts_clear_cache_script', 'ftsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'fts_clear_cache_script' );
		}
	}
	//**************************************************
	// This is for the Twitter videos, when you click show media
	//**************************************************
	function fts_load_videos_script() {
		$ftsFBfileJS = dirname(dirname(__FILE__)) . '/feed-them-social.php';
		$FTS_plugin_url = plugin_dir_url($ftsFBfileJS);
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'fts_load_videos_script' );
	}
	//**************************************************
	// this function is being called from the twitter feed it calls the ajax in this case.
	//**************************************************
	function fts_load_videos() {
		if ( !wp_verify_nonce( $_REQUEST['fts_security'], $_REQUEST['fts_time'].'load-more-nonce')) {
			exit( 'Sorry, You can\'t do that!' );
		}
		else {
			$tFinal = $_REQUEST['fts_link'];
			//strip Vimeo URL then ouput Iframe
			if (strpos($tFinal, 'vimeo') > 0) {
				if (strpos($tFinal, 'staffpicks') > 0 ) {
					$parsed_url = $tFinal;
					// var_dump(parse_url($parsed_url));
					$parsed_url = parse_url($parsed_url);
					$vimeoURLfinal = preg_replace('/\D/', '', $parsed_url["path"]);
				}
				else {
					$vimeoURLfinal = (int) substr(parse_url($tFinal, PHP_URL_PATH), 1);
					// echo $vimeoURLfinal;
				}
				// echo $vimeoURLfinal;
				echo '<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=0" class="video" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
			}
			//strip Vimeo Staffpics URL then ouput Iframe
			elseif (strpos($tFinal, 'amp.twimg.com') > 0) {
				include_once(WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php');
				$fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
				$fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');
				$fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
				$fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');
				//Use custom api info
				if (!empty($fts_twitter_custom_consumer_key) && !empty($fts_twitter_custom_consumer_secret) && !empty($fts_twitter_custom_access_token) && !empty($fts_twitter_custom_access_token_secret)) {
					$connection = new TwitterOAuthFTS(
						//Consumer Key
						$fts_twitter_custom_consumer_key,
						//Consumer Secret
						$fts_twitter_custom_consumer_secret,
						//Access Token
						$fts_twitter_custom_access_token,
						//Access Token Secret
						$fts_twitter_custom_access_token_secret
					);
				}
				//else use default info
				else {
					$connection = new TwitterOAuthFTS(
						//Consumer Key
						'dOIIcGrhWgooKquMWWXg',
						//Consumer Secret
						'qzAE4t4xXbsDyGIcJxabUz3n6fgqWlg8N02B6zM',
						//Access Token
						'1184502104-Cjef1xpCPwPobP5X8bvgOTbwblsmeGGsmkBzwdB',
						//Access Token Secret
						'd789TWA8uwwfBDjkU0iJNPDz1UenRPTeJXbmZZ4xjY'
					);
				}
				if (strpos($tFinal, 'amp.twimg.com') > 0) {
					$videosDecode = $_REQUEST['fts_post_id'];
					$fetchedTweets2 = $connection->get(
						'statuses/oembed',
						array(
							'id'         => $videosDecode,
							'widget_type'     => 'video',
							'hide_tweet'     => true,
							'hide_thread'     => true,
							'hide_media'     => false,
							'omit_script'     => false,
						)
					);
					echo $fetchedTweets2->html;
				}
				else {
					exit( 'That is not allowed. FTS!' );
				}
			}
			//strip Vine URL then ouput Iframe and script
			elseif (strpos($tFinal, 'vine') > 0 && !strpos($tFinal, '-vine') > 0) {
				// $pattern = str_replace( array( 'https://vine.co/v/', '/', 'http://vine.co/v/'), '', $tFinal);
				// $vineURLfinal = $pattern;
				echo  '<div class="fts-fluid-videoWrapper"><iframe height="281" class="fts-vine-embed" src="'.$tFinal.'/embed/simple" frameborder="0"></iframe></div>';
			}
			//strip Youtube URL then ouput Iframe and script
			elseif (strpos($tFinal, 'youtube') > 0 && !strpos($tFinal, '-youtube') > 0) {
				$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
				preg_match($pattern, $tFinal, $matches);
				$youtubeURLfinal = $matches[1];
				echo  '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
			}
			//strip Youtube URL then ouput Iframe and script
			elseif (strpos($tFinal, 'youtu.be') > 0) {
				$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
				preg_match($pattern, $tFinal, $matches);
				$youtubeURLfinal = $matches[1];
				echo  '<div class="fts-fluid-videoWrapper"><iframe height="281" class="video" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
			}
			//strip Youtube URL then ouput Iframe and script
			elseif (strpos($tFinal, 'soundcloud') > 0) {
				//Get the JSON data of song details with embed code from SoundCloud oEmbed
				$getValues=file_get_contents('http://soundcloud.com/oembed?format=js&url='.$tFinal.'&auto_play=false&iframe=true');
				//Clean the Json to decode
				$decodeiFrame=substr($getValues, 1, -2);
				//json decode to convert it as an array
				$jsonObj = json_decode($decodeiFrame);
				echo  '<div class="fts-fluid-videoWrapper">'.$jsonObj->html.'</div>';
			}
		} // end main else
		die();
	} // end of my_ajax_callback()
	//**************************************************
	// Admin menu buttons
	//**************************************************
	function Feed_Them_Main_Menu() {
		//Main Settings Page
		$main_settings_page = new FTS_settings_page();
		add_menu_page('Feed Them Social', 'Feed Them', 'manage_options', 'feed-them-settings-page', array($main_settings_page,'feed_them_settings_page'), '');
		add_submenu_page('feed-them-settings-page', __('Settings', 'feed-them-social'),  __('Settings', 'feed-them-social'), 'manage_options', 'feed-them-settings-page' );
	}
	//**************************************************
	// Admin Submenu buttons // add the word setting in place of the default menu page name 'Feed Them'
	//**************************************************
	function Feed_Them_Submenu_Pages() {
		//Facebook Options Page
		$facebook_options_page = new FTS_facebook_options_page();
		add_submenu_page(
			'feed-them-settings-page',
			__('Facebook Options', 'feed-them-social'),
			__('Facebook Options', 'feed-them-social'),
			'manage_options',
			'fts-facebook-feed-styles-submenu-page',
			array($facebook_options_page,'feed_them_facebook_options_page')
		);
		//Twitter Options Page
		$twitter_options_page = new FTS_twitter_options_page(); 
		add_submenu_page(
			'feed-them-settings-page',
			__('Twitter Options', 'feed-them-social'),
			__('Twitter Options', 'feed-them-social'),
			'manage_options',
			'fts-twitter-feed-styles-submenu-page',
			array($twitter_options_page,'feed_them_twitter_options_page')
		);
		//Pinterest Options Page
		$pinterest_options_page = new FTS_pinterest_options_page(); 
		add_submenu_page(
			'feed-them-settings-page',
			__('Pinterest Options', 'feed-them-social'),
			__('Pinterest Options', 'feed-them-social'),
			'manage_options',
			'fts-pinterest-feed-styles-submenu-page',
			array($pinterest_options_page,'feed_them_pinterest_options_page')
		);
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			//Youtube Options Page
			$youtube_options_page = new FTS_youtube_options_page();
			add_submenu_page(
				'feed-them-settings-page',
				__('YouTube Options', 'feed-them-social'),
				__('YouTube Options', 'feed-them-social'),
				'manage_options',
				'fts-youtube-feed-styles-submenu-page',
				array($youtube_options_page,'feed_them_youtube_options_page')
			);
		}
		//Instagram Options Page
		$instagram_options_page = new FTS_instagram_options_page();
		add_submenu_page(
			'feed-them-settings-page',
			__('Instagram Options', 'feed-them-social'),
			__('Instagram Options', 'feed-them-social'),
			'manage_options',
			'fts-instagram-feed-styles-submenu-page',
			array($instagram_options_page,'feed_them_instagram_options_page')
		);
		//System Info
		$system_info_page = new FTS_system_info_page();
		add_submenu_page(
			'feed-them-settings-page',
			__('System Info', 'feed-them-social'),
			__('System Info', 'feed-them-social'),
			'manage_options',
			'fts-system-info-submenu-page',
			array($system_info_page,'feed_them_system_info_page')
		);
	}
	//**************************************************
	// Admin CSS
	//**************************************************
	function feed_them_admin_css() {
		wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.css', dirname(__FILE__) ) );
		wp_enqueue_style('feed_them_admin');
	}
	//**************************************************
	// Admin System Info CSS
	//**************************************************
	function feed_them_system_info_css() {
		wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.css',  dirname(__FILE__) ) );
		wp_enqueue_style('fts-settings-admin-css');
	}
	//**************************************************
	// Admin Settings Scripts and CSS
	//**************************************************
	function feed_them_settings() {
		wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.css',  dirname(__FILE__) ) );
		wp_enqueue_style('feed_them_settings_css');
		if (isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page') {
			wp_enqueue_script( 'feed_them_style_options_color_js', plugins_url( 'admin/js/jscolor/jscolor.js',  dirname(__FILE__) ) );
		}
	}
	//**************************************************
	// Admin Premium Settings Fields
	//**************************************************
	function need_fts_premium_fields($fields) {
		$output = isset($output) ? $output : "";
		foreach ($fields as $key => $label) {
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.$label.'</div>';
			$output .= '<div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.</div>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		}//END Foreach
		return $output;
	}
	//**************************************************
	// Generic Register Settings function
	//**************************************************
	function register_settings($settings_name , $settings) {
		foreach ($settings as $key => $setting) {
			register_setting( $settings_name, $setting);
		}
	}
	//**************************************************
	// Register Facebook Style Options.
	//**************************************************
	function fts_facebook_style_options_page() {
		$fb_style_options = array(
		 'fb_app_ID',
			'fb_like_btn_color',
			'fb_language',
			'fb_show_follow_btn',
			'fb_show_follow_like_box_cover',
			'fb_show_follow_btn_where',
			'fb_header_extra_text_color',
			'fb_text_color',
			'fb_link_color',
			'fb_link_color_hover',
			'fb_feed_width',
			'fb_feed_margin',
			'fb_feed_padding',
			'fb_feed_background_color',
			'fb_grid_posts_background_color',
			'fb_border_bottom_color',
			'fts_facebook_custom_api_token',
			'fb_event_title_color',
			'fb_event_title_size',
			'fb_event_maplink_color',
			'fb_events_title_color',
			'fb_events_title_size',
			'fb_events_map_link_color',
			'fb_hide_shared_by_etc_text',
			'fts_facebook_custom_api_token_biz',
			'fb_reviews_text_color',
			'fb_reviews_backg_color',
			'fb_max_image_width',
			'fb_hide_images_in_posts',
		);
		$this->register_settings('fts-facebook-feed-style-options', $fb_style_options);
	}
	//**************************************************
	// Register Twitter Style Options.
	//**************************************************
	function fts_twitter_style_options_page() {
		$twitter_style_options = array(
			'twitter_show_follow_btn',
			'twitter_show_follow_count',
			'twitter_show_follow_btn_where',
			'twitter_allow_videos',
			'twitter_allow_shortlink_conversion',
			'twitter_full_width',
			'twitter_text_color',
			'twitter_link_color',
			'twitter_link_color_hover',
			'twitter_feed_width',
			'twitter_feed_margin',
			'twitter_feed_padding',
			'twitter_feed_background_color',
			'twitter_border_bottom_color',
			'fts_twitter_custom_consumer_key',
			'fts_twitter_custom_consumer_secret',
			'fts_twitter_custom_access_token',
			'fts_twitter_custom_access_token_secret',
			'twitter_max_image_width',
		);
		$this->register_settings('fts-twitter-feed-style-options', $twitter_style_options);
	}
	//**************************************************
	// Register Instagram Options.
	//**************************************************
	function fts_instagram_style_options_page() {
		$instagram_style_options = array(
			'fts_instagram_custom_api_token',
			'instagram_show_follow_btn',
			'instagram_show_follow_btn_where',
		);
		$this->register_settings('fts-instagram-feed-style-options', $instagram_style_options);
	}
	//**************************************************
	// Register Pinterest Options.
	//**************************************************
	function fts_pinterest_style_options_page() {
		$pinterest_style_options = array(
			'pinterest_show_follow_btn',
			'pinterest_show_follow_btn_where',
		);
		$this->register_settings('fts-pinterest-feed-style-options', $pinterest_style_options);
	}
	//**************************************************
	// Register YouTube Options.
	//**************************************************
	function fts_youtube_style_options_page() {
		$youtube_style_options = array(
			'youtube_show_follow_btn',
			'youtube_show_follow_btn_where',
			'youtube_custom_api_token',
		);
		$this->register_settings('fts-youtube-feed-style-options', $youtube_style_options);
	}
	//**************************************************
	// Register Free Version Settings.
	//**************************************************
	function fts_settings_page_register_settings() {
		$settings = array(
			'instagram_show_follow_btn',
			'fts_admin_bar_menu',
			'fts_clear_cache_developer_mode',
			'fts-date-and-time-format',
			'fts-timezone',
			'fts_fix_magnific',
			'fts-color-options-settings-custom-css',
			'fts-color-options-main-wrapper-css-input',
			'fts-powered-text-options-settings',
			'fts-slicker-instagram-icon-center',
			'fts-slicker-instagram-container-image-size',
			'fts-slicker-instagram-container-hide-date-likes-comments',
			'fts-slicker-instagram-container-position',
			'fts-slicker-instagram-container-animation',
			'fts-slicker-instagram-container-margin',
			'fts_fix_loadmore',
			'fts-custom-date',
			'fts-custom-time',
			'fts_language_second',
			'fts_language_seconds',
			'fts_language_minute',
			'fts_language_minutes',
			'fts_language_hour',
			'fts_language_hours',
			'fts_language_day',
			'fts_language_days',
			'fts_language_week',
			'fts_language_weeks',
			'fts_language_month',
			'fts_language_months',
			'fts_language_year',
			'fts_language_years',
			'fts_language_ago'
		);
		$this->register_settings('feed-them-social-settings', $settings);
	}
	//**************************************************
	// Social Follow Button.
	//**************************************************
	function social_follow_button($feed, $user_id, $access_token = NULL) {
		
		global $channel_id, $playlist_id, $username_subscribe_btn, $username;
		$output = '';	
		switch ($feed) {
		case 'facebook':
				//Facebook settings options for follow button
				$fb_show_follow_btn = get_option('fb_show_follow_btn');
				$fb_show_follow_like_box_cover = get_option('fb_show_follow_like_box_cover');
				$language_option_check = get_option('fb_language');
				$fb_app_ID = get_option('fb_app_ID');
				
				if (isset($language_option_check) && $language_option_check !== 'Please Select Option') {
					$language_option = get_option('fb_language', 'en_US');
				}
				else {
							$language_option = 'en_US';
				}
				$fb_like_btn_color = get_option('fb_like_btn_color', 'light');
			//	var_dump( $fb_like_btn_color ); /* outputs 'default_value' */
				
				$show_faces = $fb_show_follow_btn == 'like-button-share-faces' || $fb_show_follow_btn == 'like-button-faces' || $fb_show_follow_btn == 'like-box-faces' ? 'true' : 'false';
				$share_button = $fb_show_follow_btn == 'like-button-share-faces' || $fb_show_follow_btn == 'like-button-share' ? 'true' : 'false';
				$page_cover = $fb_show_follow_like_box_cover == 'fb_like_box_cover-yes' ? 'true' : 'false';
				if(!isset($_POST['fts_facebook_script_loaded'])){
							$output .='<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/'.$language_option.'/sdk.js#xfbml=1&appId='.$fb_app_ID.'&version=v2.3";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, "script", "facebook-jssd"));</script>';
							$_POST['fts_facebook_script_loaded'] = 'yes';
				}	
				//Page Box
				if($fb_show_follow_btn == 'like-box' || $fb_show_follow_btn == 'like-box-faces') {
					$output .='<div class="fb-page" data-href="https://www.facebook.com/'.$user_id.'" data-hide-cover="'.$page_cover.'" data-show-facepile="'.$show_faces.'" data-show-posts="false"></div>';
				}
				//Like Button
				else{
					$output .='<div class="fb-like" data-href="https://www.facebook.com/'.$user_id.'" data-layout="standard" data-action="like" data-colorscheme="'.$fb_like_btn_color.'" data-show-faces="'.$show_faces.'" data-share="'.$share_button.'" data-width:"100%"></div>';
				}
				return $output;
			break;
		case 'instagram':
			$output .='<a href="https://instagram.com/'.$user_id.'/" target="_blank">Follow on Instagram</a>';
			print $output;
			break;
		case 'twitter':
			if(!isset($_POST['fts_twitter_script_loaded'])){
				$output .='<script>window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return t;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>';
				$_POST['fts_twitter_script_loaded'] = 'yes';	
			}
			$output .='<a class="twitter-follow-button" href="https://twitter.com/'.$user_id.'" data-show-count="false" data-lang="en"> Follow @'.$user_id.'</a>';
			print $output;
			break;
		case 'pinterest':
				if(!isset($_POST['fts_pinterest_script_loaded'])){
					$output .='
					<script> 
						jQuery(function () {
						   	//then load the JavaScript file
						    jQuery.getScript("//assets.pinterest.com/js/pinit.js");
						});
					</script>
					';
					$_POST['fts_pinterest_script_loaded'] = 'yes';
				}
				
				$output .='<a data-pin-do="buttonFollow" href="http://www.pinterest.com/'.$user_id.'/">Follow @'.$user_id.'</a>';
				
				return $output;
			break;
		case 'youtube':
				if(!isset($_POST['fts_youtube_script_loaded'])){
					$output .='<script src="https://apis.google.com/js/platform.js"></script>';
					$_POST['fts_youtube_script_loaded'] = 'yes';
				}
					if($channel_id == '' && $playlist_id == '' && $username !== '' || $playlist_id !== '' && $username_subscribe_btn !== ''){
							 
								if($username_subscribe_btn !== ''){		
										$output .='<div class="g-ytsubscribe" data-channel="'.$username_subscribe_btn.'" data-layout="full" data-count="default"></div>';
								}
								else {
										$output .='<div class="g-ytsubscribe" data-channel="'.$user_id.'" data-layout="full" data-count="default"></div>';
								}
								
					}
					elseif($channel_id !== '' && $playlist_id !== '' || $channel_id !== '') {	
						$output .='<div class="g-ytsubscribe" data-channelid="'.$channel_id.'" data-layout="full" data-count="default"></div>';
					}
				print $output;
			break;
		}
	}
	//**************************************************
	// Color Options for Facebook.
	//**************************************************
	function fts_color_options_head_css() { ?>
		<style type="text/css"><?php echo get_option('fts-color-options-main-wrapper-css-input');?></style>
	<?php
	}
	//**************************************************
	// Color Options CSS for Facebook.
	//**************************************************
	function fts_fb_color_options_head_css() {
		$fb_header_extra_text_color = get_option('fb_header_extra_text_color');
		$fb_text_color = get_option('fb_text_color');
		$fb_link_color = get_option('fb_link_color');
		$fb_link_color_hover = get_option('fb_link_color_hover');
		$fb_feed_width = get_option('fb_feed_width');
		$fb_feed_margin = get_option('fb_feed_margin');
		$fb_feed_padding = get_option('fb_feed_padding');
		$fb_feed_background_color = get_option('fb_feed_background_color');
		$fb_grid_posts_background_color = get_option('fb_grid_posts_background_color');
		$fb_border_bottom_color = get_option('fb_border_bottom_color');
		$fb_grid_posts_background_color = get_option('fb_grid_posts_background_color');
		$fb_reviews_backg_color = get_option('fb_reviews_backg_color');
		$fb_reviews_text_color = get_option('fb_reviews_text_color');
		$fb_max_image_width = get_option('fb_max_image_width');
		
		$fb_events_title_color = get_option('fb_events_title_color');
		$fb_events_title_size = get_option('fb_events_title_size');
		$fb_events_maplink_color = get_option('fb_events_map_link_color');
		
		$twitter_hide_profile_photo = get_option('twitter_hide_profile_photo');
		$twitter_text_color = get_option('twitter_text_color');
		$twitter_link_color = get_option('twitter_link_color');
		$twitter_link_color_hover = get_option('twitter_link_color_hover');
		$twitter_feed_width = get_option('twitter_feed_width');
		$twitter_feed_margin = get_option('twitter_feed_margin');
		$twitter_feed_padding = get_option('twitter_feed_padding');
		$twitter_feed_background_color = get_option('twitter_feed_background_color');
		$twitter_border_bottom_color = get_option('twitter_border_bottom_color');
		$twitter_max_image_width = get_option('twitter_max_image_width');?>
<style type="text/css"><?php if (!empty($fb_header_extra_text_color)) { ?>.fts-jal-single-fb-post .fts-jal-fb-user-name { color:<?php echo $fb_header_extra_text_color ?>!important; }
<?php }if (!empty($fb_text_color)) { ?>
.fts-simple-fb-wrapper .fts-jal-single-fb-post,
.fts-simple-fb-wrapper .fts-jal-fb-description-wrap,
.fts-simple-fb-wrapper .fts-jal-fb-post-time,
.fts-slicker-facebook-posts .fts-jal-single-fb-post,
.fts-slicker-facebook-posts .fts-jal-fb-description-wrap,
.fts-slicker-facebook-posts .fts-jal-fb-post-time { color:<?php echo $fb_text_color ?>!important; }
<?php }if (!empty($fb_link_color)) { ?>
.fts-simple-fb-wrapper .fts-jal-single-fb-post a,
.fts-fb-load-more-wrapper .fts-fb-load-more,
.fts-slicker-facebook-posts .fts-jal-single-fb-post a,
.fts-fb-load-more-wrapper .fts-fb-load-more { color:<?php echo $fb_link_color ?>!important; }
<?php }if (!empty($fb_link_color_hover)) { ?>
.fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover,
.fts-simple-fb-wrapper .fts-fb-load-more:hover,
.fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover,
.fts-slicker-facebook-posts .fts-fb-load-more:hover { color:<?php echo $fb_link_color_hover ?>!important; }
<?php }if (!empty($fb_feed_width)) { ?>
.fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper { max-width:<?php echo $fb_feed_width ?> !important; }
<?php }if (!empty($fb_max_image_width)) { ?>
.fts-fb-large-photo, .fts-jal-fb-vid-wrap { max-width:<?php echo $fb_max_image_width ?>!important;}
<?php }if (!empty($fb_events_title_color)) { ?>
.fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name { color:<?php echo $fb_events_title_color ?>!important; }
<?php }if (!empty($fb_events_title_size)) { ?>
.fts-simple-fb-wrapper .fts-events-list-wrap a.fts-jal-fb-name { font-size:<?php echo $fb_events_title_size ?>!important; line-height: <?php echo $fb_events_title_size ?>!important; }
<?php }if (!empty($fb_events_maplink_color)) { ?>
.fts-simple-fb-wrapper a.fts-fb-get-directions { color:<?php echo $fb_events_maplink_color ?>!important; }
<?php }if (!empty($fb_feed_margin)) { ?>
.fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper { margin:<?php echo $fb_feed_margin ?> !important; }
<?php }if (!empty($fb_feed_padding)) { ?>
.fts-simple-fb-wrapper { padding:<?php echo $fb_feed_padding ?>!important; }
<?php }if (!empty($fb_feed_background_color)) { ?>
.fts-simple-fb-wrapper, .fts-fb-load-more-wrapper .fts-fb-load-more { background:<?php echo $fb_feed_background_color ?>!important; }
<?php }if (!empty($fb_grid_posts_background_color)) { ?>
.fts-slicker-facebook-posts .fts-jal-single-fb-post { background:<?php echo $fb_grid_posts_background_color ?>!important; }
<?php }if (!empty($fb_border_bottom_color)) { ?>
.fts-slicker-facebook-posts .fts-jal-single-fb-post, .fts-jal-single-fb-post { border-bottom:1px solid <?php echo $fb_border_bottom_color ?>!important; }
<?php }if (!empty($fb_reviews_backg_color)) { ?>
.fts-review-star { background:<?php echo $fb_reviews_backg_color ?>!important; }
<?php }if (!empty($fb_reviews_text_color)) { ?>
.fts-review-star { color:<?php echo $fb_reviews_text_color ?>!important; }
<?php }if (!empty($twitter_text_color)) { ?>
.tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before, a span.fts-video-loading-notice { color:<?php echo $twitter_text_color ?>!important; }
<?php }if (!empty($twitter_link_color)) { ?>
.tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a, .twitter-followers-fts a  { color:<?php echo $twitter_link_color ?>!important; }
<?php }if (!empty($twitter_link_color_hover)) { ?>
.tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply { color:<?php echo $twitter_link_color_hover ?>!important; }
<?php }if (!empty($twitter_feed_width)) { ?>
.fts-twitter-div { max-width:<?php echo $twitter_feed_width ?> !important; }
<?php }if (!empty($twitter_feed_margin)) { ?>
.fts-twitter-div { margin:<?php echo $twitter_feed_margin ?> !important; }
<?php }if (!empty($twitter_feed_padding)) { ?>
.fts-twitter-div { padding:<?php echo $twitter_feed_padding ?>!important; }
<?php }if (!empty($twitter_feed_background_color)) { ?>
.fts-twitter-div { background:<?php echo $twitter_feed_background_color ?>!important; }
<?php }if (!empty($twitter_border_bottom_color)) { ?>
.tweeter-info { border-bottom:1px solid <?php echo $twitter_border_bottom_color ?>!important; }
<?php }if (!empty($twitter_max_image_width)) { ?>
.fts-twitter-link-image { max-width:<?php echo $twitter_max_image_width ?>!important; display:block; }
<?php } ?>
</style>
	<?php
	}
	//**************************************************
	// FTS Powered By.
	//**************************************************
	function fts_powered_by_js() {
		wp_enqueue_script( 'fts_powered_by_js', plugins_url( 'feeds/js/powered-by.js',  dirname(__FILE__) ), array( 'jquery' )
		);
	}
	//**************************************************
	// Facebook List of Events Form.
	//**************************************************
	function fts_facebook_list_of_events_form($save_options = false) {
		if ($save_options) {
			$fb_event_id_option = get_option('fb_event_id');
			$fb_event_post_count_option = get_option('fb_event_post_count');
			$fb_event_title_option = get_option('fb_event_title_option');
			$fb_event_description_option = get_option('fb_event_description_option');
			$fb_event_word_count_option = get_option('fb_event_word_count_option');
			$fts_bar_fb_prefix = 'fb_event_';
			$fb_load_more_option = get_option('fb_event_fb_load_more_option');
			$fb_load_more_style = get_option('fb_event_fb_load_more_style');
			$facebook_popup = get_option('fb_event_facebook_popup');
		}
		$fb_event_id_option = isset($fb_event_id_option) ? $fb_event_id_option : "";
		$output = '<div class="fts-facebook_event-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
			$output .= '<h2>'.__('Facebook List of Events Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$output .= '<div class="instructional-text inst-text-facebook-page">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">'.__('Facebook Page ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap fb_page_list_of_events_id">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Event ID (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="fb_page_list_of_events_id" id="fb_page_list_of_events_id" class="feed-them-social-admin-input" value="'.$fb_event_id_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		// Facebook Height Option
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
		$output .= '<input type="text" name="facebook_event_height" id="facebook_event_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'e" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/facebook-event-settings-fields.php');
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
				//PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
		}
		else {
			$fields = array(
				__('# of Posts (default 5)', 'feed-them-social'),
				__('Show the Event Title', 'feed-them-social'),
				__('Show the Event Description', 'feed-them-social'),
				__('Amount of words per post', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Display Posts in Grid', 'feed-them-social'),
			);
			$output .=  $this->need_fts_premium_fields($fields);
		}
		if ($save_options == false) {
			$output .=  $this->generate_shortcode('updateTextArea_fb_list_of_events();', 'Facebook List of Events Feed Shortcode', 'facebook-event-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		return $output;
	}

	//**************************************************
	// Facebook Single Event Form.
	//**************************************************
	function fts_facebook_event_form($save_options = false) {
		if ($save_options) {
			$fb_event_id_option = get_option('fb_event_id');
			$fb_event_post_count_option = get_option('fb_event_post_count');
			$fb_event_title_option = get_option('fb_event_title_option');
			$fb_event_description_option = get_option('fb_event_description_option');
			$fb_event_word_count_option = get_option('fb_event_word_count_option');
			$fts_bar_fb_prefix = 'fb_event_';
			$fb_load_more_option = get_option('fb_event_fb_load_more_option');
			$fb_load_more_style = get_option('fb_event_fb_load_more_style');
			$facebook_popup = get_option('fb_event_facebook_popup');
		}
		$fb_event_id_option = isset($fb_event_id_option) ? $fb_event_id_option : "";
		$output = '<div class="fts-facebook_event-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
			$output .= '<h2>'.__('Facebook Event Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$output .= '<div class="instructional-text inst-text-facebook-page">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">'.__('Facebook Page Event ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap fb_event_id">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Event ID (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="fb_event_id" id="fb_event_id" class="feed-them-social-admin-input" value="'.$fb_event_id_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		// Facebook Height Option
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
		$output .= '<input type="text" name="facebook_event_height" id="facebook_event_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'e" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/facebook-event-settings-fields.php');
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
				//PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
		}
		else {
			$fields = array(
				__('# of Posts (default 5)', 'feed-them-social'),
				__('Show the Event Title', 'feed-them-social'),
				__('Show the Event Description', 'feed-them-social'),
				__('Amount of words per post', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Display Posts in Grid', 'feed-them-social'),
			);
			$output .=  $this->need_fts_premium_fields($fields);
		}
		if ($save_options == false) {
			$output .=  $this->generate_shortcode('updateTextArea_fb_event();', 'Facebook Event Feed Shortcode', 'facebook-list-of-events-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Facebook Group Form.
	//**************************************************
	function fts_facebook_group_form($save_options = false) {
		if ($save_options) {
			$fb_group_id_option = get_option('fb_group_id');
			$fb_group_post_count_option = get_option('fb_group_post_count');
			$fb_group_title_option = get_option('fb_group_title_option');
			$fb_group_description_option = get_option('fb_group_description_option');
			$fb_group_word_count_option = get_option('fb_group_word_count_option');
			$fts_bar_fb_prefix = 'fb_group_';
			$fb_load_more_option = get_option('fb_group_fb_load_more_option');
			$fb_load_more_style = get_option('fb_group_fb_load_more_style');
			$facebook_popup = get_option('fb_group_facebook_popup');
		}
		$fb_group_id_option = isset($fb_group_id_option) ? $fb_group_id_option : "";
		$output = '<div class="fts-facebook_group-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">';
			$output .= '<h2>'.__('Facebook Group Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$output .= '<div class="instructional-text">'.__('You must copy your ', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">'.__('Facebook Group ID ', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap fb_group_id">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Group ID (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="fb_group_id" id="fb_group_id" class="feed-them-social-admin-input" value="'.$fb_group_id_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		// Facebook Height Option
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
		$output .= '<input type="text" name="facebook_group_height" id="facebook_group_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		//  $output .= '<!-- Using this for a future update <div class="feed-them-social-admin-input-wrap">
		//   <div class="feed-them-social-admin-input-label">'.__('Customized Group Name', 'feed-them-social').'</div>
		//  <select id="fb_group_custom_name" class="feed-them-social-admin-input">
		//   <option selected="selected" value="yes">'.__('My group name is custom', 'feed-them-social').'</option>
		//  <option value="no">'.__('My group name is number based', 'feed-them-social').'</option>
		// </select>
		// <div class="clear"></div>
		// </div>
		// /feed-them-social-admin-input-wrap-->';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/facebook-group-settings-fields.php');
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
				//PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('# of Posts (default 5)', 'feed-them-social'),
				__('Show the Group Title', 'feed-them-social'),
				__('Show the Group Description', 'feed-them-social'),
				__('Amount of words per post', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Display Posts in Grid', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
		}
		if ($save_options == false) {
			$output .= $this->generate_shortcode('updateTextArea_fb_group();', 'Facebook Group Feed Shortcode', 'facebook-group-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Facebook Page Form.
	//**************************************************
	function fts_facebook_page_form($save_options = false) {
		if ($save_options) {
			$fb_page_id_option = get_option('fb_page_id');
			$fb_page_posts_displayed_option = get_option('fb_page_posts_displayed');
			$fb_page_post_count_option = get_option('fb_page_post_count');
			$fb_page_title_option = get_option('fb_page_title_option');
			$fb_page_description_option = get_option('fb_page_description_option');
			$fb_page_word_count_option = get_option('fb_page_word_count_option');
			$fts_bar_fb_prefix = 'fb_page_';
			$fb_load_more_option = get_option('fb_page_fb_load_more_option');
			$fb_load_more_style = get_option('fb_page_fb_load_more_style');
			$facebook_popup = get_option('fb_page_facebook_popup');
		}
		$output = '<div class="fts-facebook_page-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">';
			
			// Check to see if token is in place otherwise show a message letting person no what they need to do
			$facebookOptions = get_option('fts_facebook_custom_api_token') ? 'Yes' : 'No' ;
			$output .= isset($facebookOptions) && $facebookOptions !== 'No' ? ''. "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a Facebook API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before trying to view your feed.</div>'. "\n";
			// end custom message for requiring token
			
			
			if(is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
			$facebookOptions2 = get_option('fts_facebook_custom_api_token_biz') ? 'Yes' : 'No' ;
				// Check to see if token is in place otherwise show a message letting person no what they need to do
			$output .= isset($facebookOptions2) && $facebookOptions2 !== 'No' ? ''. "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a Facebook Page Reviews API Token to our <a href="admin.php?page=fts-facebook-feed-styles-submenu-page">Facebook Options</a> page before trying to view your Facebook Reviews feed.</div>'. "\n";
			// end custom message for requiring token
			}
			
			
			$output .= '<h2>'.__('Facebook Page Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$fb_page_id_option = isset($fb_page_id_option) ? $fb_page_id_option : "";
		// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			// FACEBOOK FEED TYPE
			$output .= '<div class="feed-them-social-admin-input-wrap" id="fts-social-selector">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
			$output .= '<select name="facebook-messages-selector" id="facebook-messages-selector" class="feed-them-social-admin-input">';
			$output .= '<option value="page">'.__('Facebook Page', 'feed-them-social').'</option>';
			$output .= '<option value="events">'.__('Facebook Page List of Events', 'feed-them-social').'</option>';
			$output .= '<option value="group">'.__('Facebook Group', 'feed-them-social').'</option>';
			$output .= '<option value="event">'.__('Facebook Single Event', 'feed-them-social').'</option>';
			$output .= '<option value="album_photos">'.__('Facebook Album Photos', 'feed-them-social').'</option>';
			$output .= '<option value="albums">'.__('Facebook Album Covers', 'feed-them-social').'</option>';
			$output .= '<option value="album_videos">'.__('Facebook Videos', 'feed-them-social').'</option>';
			$output .= '<option value="reviews">'.__('Facebook Page Reviews', 'feed-them-social').'</option>';
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		};
		// INSTRUCTIONAL TEXT FOR FACEBOOK TYPE SELECTION. PAGE, GROUP, EVENT, ALBUMS, ALBUM COVERS AND HASH TAGS
		$output .= '<div class="instructional-text facebook-message-generator page inst-text-facebook-page" style="display:block;">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">'.__('Facebook Page ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
			<div class="instructional-text facebook-message-generator group inst-text-facebook-group">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">'.__('Facebook Group ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
			<div class="instructional-text facebook-message-generator event inst-text-facebook-event">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">'.__('Facebook Event ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
			<div class="instructional-text facebook-message-generator album_photos inst-text-facebook-album-photos">'.__('To show a specific Album copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">'.__('Facebook Album ID', 'feed-them-social').'</a> '.__('and paste it in the second input below. If you want to show all your uploaded photos leave the Album ID input blank.', 'feed-them-social').'</div>
			<div class="instructional-text facebook-message-generator albums inst-text-facebook-albums">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">'.__('Facebook Album Covers ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
			<div class="instructional-text facebook-message-generator video inst-text-facebook-video">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-id-and-video-gallery-id" target="_blank">'.__('Facebook ID and Video Album ID', 'feed-them-social').'</a> '.__('and paste them below.', 'feed-them-social').'</div>';
					if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			// this is for the facebook videos
			$output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">Premium Version Required</a><br/>The Facebook video feed allows you to view your uploaded videos from facebook. See these great examples and options of all the different ways you can bring new life to your wordpress site! <a href="http://feedthemsocial.com/facebook-videos-demo/" target="_blank">View Demo</a></div>';
			// this is for the facebook reviews
			$output .= '<div class="feed-them-social-admin-input-wrap fts-premium-options-message2" style="display:none;"><a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-facebook-reviews/">Facebook Reviews Required</a><br/>The Facebook Reviews feed allows you to view all of the reviews people have made on your Facebook Page. See these great examples and options of all the different ways you can display your Facebook Page Reviews on your website. <a href="http://feedthemsocial.com/facebook-page-reviews-demo/" target="_blank">View Demo</a></div>';
					}
		// FACEBOOK PAGE ID
				if (isset($_GET['page']) && $_GET['page'] !== 'fts-bar-settings-page') {
		$output .= '<div class="fb-options-wrap">';
				}
		$output .= '<div class="feed-them-social-admin-input-wrap fb_page_id ">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook ID (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="fb_page_id" id="fb_page_id" class="feed-them-social-admin-input" value="'.$fb_page_id_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		// FACEBOOK ALBUM PHOTOS ID
		$output .= '<div class="feed-them-social-admin-input-wrap fb_album_photos_id" style="display:none;">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Album ID ', 'feed-them-social').'<br/><small>'.__('Leave blank to show all uploaded photos', 'feed-them-social').'</small></div>';
		$output .= '<input type="text" name="fb_album_id" id="fb_album_id" class="feed-them-social-admin-input" value="" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		$fb_page_posts_displayed_option = isset($fb_page_posts_displayed_option) ? $fb_page_posts_displayed_option : "";
		// FACEBOOK PAGE POST TYPE VISIBLE
		$output .= '<div class="feed-them-social-admin-input-wrap facebook-post-type-visible">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Post Type Visible', 'feed-them-social').'</div>';
		$output .= '<select name="fb_page_posts_displayed" id="fb_page_posts_displayed" class="feed-them-social-admin-input">';
		$output .= '<option '.selected($fb_page_posts_displayed_option, 'page_only', false ) .' value="page_only">'.__('Display Posts made by Page only', 'feed-them-social').'</option>';
		$output .= '<option '.selected($fb_page_posts_displayed_option, 'others_only', false ) .' value="others_only">'.__('Display Posts made by Others', 'feed-them-social').'</option>';
		$output .= '</select>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php') && !is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
			
			include($this->premium.'admin/facebook-page-settings-fields.php');
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
				//PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
			
		}
		elseif(is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
			
			// these are the new options for reviews only
			include($this->facebook_reviews.'admin/facebook-review-settings-fields.php');	
				
			include($this->premium.'admin/facebook-page-settings-fields.php');
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page') {
				//PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
		}
		elseif(is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && !is_plugin_active('feed-them-premium/feed-them-premium.php')){
			// include($this->facebook_reviews.'admin/facebook-page-settings-fields.php');
			
				// these are the new options for reviews only
			 include($this->facebook_reviews.'admin/facebook-review-settings-fields.php');	
 
	   // these are the additional options only for reviews from premium		
			 include($this->facebook_reviews.'admin/facebook-loadmore-settings-fields.php');	
				
				//Create Need Premium Fields
			$fields = array(
				__('# of Posts (default 5)', 'feed-them-social'),
				__('Show the Page Title', 'feed-them-social'),
				__('Show the Page Description', 'feed-them-social'),
				__('Amount of words per post', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Display Posts in Grid', 'feed-them-social'),
				__('Center Grid', 'feed-them-social'),
				__('Grid Stack Animation', 'feed-them-social'),
				__('Align Like button or Box', 'feed-them-social'),
				__('Hide Like button or Box', 'feed-them-social'),
			);
			$output .= '<div class="need-for-premium-fields-wrap">'.$this->need_fts_premium_fields($fields).'</div>';
		}
		else {
			
			//Create Need Premium Fields
			$fields = array(
				__('# of Posts (default 5)', 'feed-them-social'),
				__('Show the Page Title', 'feed-them-social'),
				__('Show the Page Description', 'feed-them-social'),
				__('Amount of words per post', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Display Posts in Grid', 'feed-them-social'),
				__('Center Grid', 'feed-them-social'),
				__('Grid Stack Animation', 'feed-them-social'),
				__('Align Like button or Box', 'feed-them-social'),
				__('Hide Like button or Box', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
		}
		// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			// FACEBOOK HEIGHT OPTION
			$output .= '<div class="feed-them-social-admin-input-wrap twitter_name fixed_height_option">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="facebook_page_height" id="facebook_page_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			// FACEBOOK super gallery
			// $output .= '<div class="feed-them-social-admin-input-wrap facebook_name" style="display:none">';
			// $output .= '<div class="feed-them-social-admin-input-label">Super Facebook Gallery</div>';
			// $output .= '<select id="facebook-custom-gallery" name="facebook-custom-gallery" class="feed-them-social-admin-input"><option value="no" >No</option><option value="yes" >Yes. See Super Facebook Gallery Options below.</option></select>';
			// $output .= '<div class="clear"></div>';
			// $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			// These options are only for FB album photos and covers
			// SUPER FACEBOOK GALLERY OPTIONS
			$output .= '<div class="fts-super-facebook-options-wrap" style="display:none">';
			// FACEBOOK IMAGE HEIGHT
			$output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Facebook Image Width', 'feed-them-social').'<br/><small>'.__('Max width is 640px', 'feed-them-social').'</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-width" id="fts-slicker-facebook-container-image-width" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="clear"></div> </div>';
			// FACEBOOK IMAGE WIDTH
			$output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Facebook Image Height', 'feed-them-social').'<br/><small>'.__('Max width is 640px', 'feed-them-social').'</small></div>
	           <input type="text" name="fts-slicker-instagram-container-image-height" id="fts-slicker-facebook-container-image-height" class="feed-them-social-admin-input" value="250px" placeholder="">
	           <div class="clear"></div> </div>';
			// FACEBOOK SPACE BETWEEN PHOTOS
			$output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('The space between photos', 'feed-them-social').'</div>
	           <input type="text" name="fts-slicker-facebook-container-margin" id="fts-slicker-facebook-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
	           <div class="clear"></div></div>';
			// HIDE DATES, LIKES AND COMMENTS ETC
			$output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Hide Date, Likes and Comments', 'feed-them-social').'<br/><small>'.__('Good for image sizes under 120px', 'feed-them-social').'</small></div>
	       		 <select id="fts-slicker-facebook-container-hide-date-likes-comments" name="fts-slicker-facebook-container-hide-date-likes-comments" class="feed-them-social-admin-input">
	        	  <option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
												
			// CENTER THE FACEBOOK CONTAINER
			$output .= '<div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_container"><div class="feed-them-social-admin-input-label">'.__('Center Facebook Container', 'feed-them-social').'</div>
	        	<select id="fts-slicker-facebook-container-position" name="fts-slicker-facebook-container-position" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
			// ANIMATE PHOTO POSITIONING
			$output .= ' <div class="feed-them-social-admin-input-wrap" id="facebook_super_gallery_animate"><div class="feed-them-social-admin-input-label">'.__('Image Stacking Animation On', 'feed-them-social').'<br/><small>'.__('This happens when resizing browsert', 'feed-them-social').'</small></div>
	        	 <select id="fts-slicker-facebook-container-animation" name="fts-slicker-facebook-container-animation" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
			// POSITION IMAGE LEFT RIGHT
			$output .= '<div class="instructional-text" style="display: block;">'.__('These options allow you to make the thumbnail larger if you do not want to see black bars above or below your photos.', 'feed-them-social').' <a href="http://www.slickremix.com/docs/fit-thumbnail-on-facebook-galleries/" target="_blank">'.__('View Examples', 'feed-them-social').'</a> '.__('and simple details or leave default options.', 'feed-them-social').'</div>
			<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Make photo larger', 'feed-them-social').'<br/><small>'.__('Helps with blackspace', 'feed-them-social').'</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-lr" name="fts-slicker-facebook-image-position-lr" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -50%. -0% '.__('is default', 'feed-them-social').'">
	           <div class="clear"></div></div>';
			// POSITION IMAGE TOP
			$output .= ' <div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Image Position Top', 'feed-them-social').'<br/><small>'.__('Helps center image', 'feed-them-social').'</small></div>
				<input type="text" id="fts-slicker-facebook-image-position-top" name="fts-slicker-facebook-image-position-top" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -10%. -0% '.__('is default', 'feed-them-social').'">
				<div class="clear"></div></div>';
			$output .= '</div><!--fts-super-facebook-options-wrap-->';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				//PREMIUM LOAD MORE SETTINGS
				include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
		}
		if ($save_options == false) {
			$output .= $this->generate_shortcode('updateTextArea_fb_page();', 'Facebook Page Feed Shortcode', 'facebook-page-final-shortcode');
	 if (isset($_GET['page']) && $_GET['page'] !== 'fts-bar-settings-page') {
			$output .= '</div>'; // END fb-options-wrap
					}
				$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
		}
		$output .= '</div><!--/fts-facebook_page-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Twitter Form.
	//**************************************************
	function fts_twitter_form($save_options = false) {
		if ($save_options) {
			$twitter_name_option = get_option('twitter_name');
			$tweets_count_option = get_option('tweets_count');
			$twitter_popup_option = get_option('twitter_popup_option');
			$twitter_hashtag_etc_name = get_option('twitter_hashtag_etc_name');
		}
			
		$twitter_name_option = isset($twitter_name_option) ? $twitter_name_option : "";
		$twitter_hashtag_etc_name = isset($twitter_hashtag_etc_name) ? $twitter_hashtag_etc_name : "";
		$output = '<div class="fts-twitter-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">';
			
					// Check to see if token is in place otherwise show a message letting person no what they need to do
			$twitterOptions4 = get_option('fts_twitter_custom_access_token_secret') ? 'Yes' : 'No' ;
			$output .= isset($twitterOptions4) && $twitterOptions4 !== 'No' ? ''. "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add Twitter API Tokens to our <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">Twitter Options</a> page before trying to view your feed.</div>'. "\n";
			// end custom message for requiring token
			
			
			$output .= '<h2>'.__('Twitter Shortcode Generator', 'feed-them-social').'</h2>';
		}
		// TWITTER FEED TYPE
				$output .= '<div class="feed-them-social-admin-input-wrap twitter-gen-selection">';
				$output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
				$output .= '<select name="twitter-messages-selector" id="twitter-messages-selector" class="feed-them-social-admin-input">';
				$output .= '<option value="user">'.__('User Feed', 'feed-them-social').'</option>';
				$output .= '<option value="hashtag">'.__('#hashtag, @person, or single words', 'feed-them-social').'</option>';
				//$output .= '<option value="hashtag">Facebook Hashtag</option>';
				$output .= '</select>';
				$output .= '<div class="clear"></div>';
				$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
				
		$output .= '<div class="twitter-hashtag-etc-wrap">';
		$output .= '<h3>'.__('Twitter Search', 'feed-them-social').'</h3>';
		$output .= '<div class="instructional-text">'.__('You can use #hashtag, @person, or single words. For example, weather or weather-channel.<br/><br/>If you want to filter a specific users hashtag copy this example into the first input below and replace the user_name and YourHashtag name. DO NOT remove the from: or %# characters. NOTE: Only displays last 7 days worth of Tweets. <strong style="color:#225DE2;">from:user_name%#YourHashtag</strong>', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_hashtag_etc_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Twitter Search Name (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="twitter_hashtag_etc_name" id="twitter_hashtag_etc_name" class="feed-them-social-admin-input" value="'.$twitter_hashtag_etc_name.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		$output .= '</div><!--/twitter-hashtag-etc-wrap-->';
		
		
		$output .= '<div class="instructional-text"><span class="hashtag-option-small-text">'.__('Twitter Name is only required if you want to show a', 'feed-them-social').' <a href="admin.php?page=fts-twitter-feed-styles-submenu-page">'.__('Follow Button', 'feed-them-social').'</a>.</span><span class="must-copy-twitter-name">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-twitter-name/" target="_blank">'.__('Twitter Name', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</span></div>';
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Twitter Name', 'feed-them-social').' <span class="hashtag-option-not-required">'.__('(required)', 'feed-them-social').'</span></div>';
		$output .= '<input type="text" name="twitter_name" id="twitter_name" class="feed-them-social-admin-input" value="'.$twitter_name_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Twitter Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="twitter_height" id="twitter_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		}
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/twitter-settings-fields.php');
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('# of Tweets (default 5)', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
		}
		if ($save_options == false) {
			$output .= $this->generate_shortcode('updateTextArea_twitter();', 'Twitter Feed Shortcode', 'twitter-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-twitter-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Vine Form.
	//**************************************************
	function fts_vine_form() {
		$output = '<div class="fts-vine-shortcode-form">';
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form vine-shortcode-form" id="fts-vine-form">';
			$output .= '<h2>'.__('Vine Shortcode Generator', 'feed-them-social').'</h2>';
			
			$output .= '<div class="instructional-text">'.__('You can copy any', 'feed-them-social').' <a href="http://www.slickremix.com/docs/get-your-vine-video-id" target="_blank">'.__('Vine Video ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.<br/>Add more videos by adding a comma(,) after each id. Limit is 6 in the Free Version.<br/>For example: ee59033wulP,eBVBFTUzUHY', 'feed-them-social').'</div>';
			$output .= '<div class="feed-them-social-admin-input-wrap vine_id">';
		
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Video ID or IDs (required)', 'feed-them-social').'</div>';
			$output .= '<input type="text" name="vine_id" id="vine_id" class="feed-them-social-admin-input" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Max width of thumbnail', 'feed-them-social').'<br/><small>'.__('480px is max suggested', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="vine_maxwidth" id="vine_maxwidth" class="feed-them-social-admin-input" value="" placeholder="200px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Space between thumbnails', 'feed-them-social').'<br/><small>'.__('Leave blank for default none', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="space_between_photos" id="space_between_photos" class="feed-them-social-admin-input" value="" placeholder="4px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Rounded Thumb Corner Amount', 'feed-them-social').'<br/><small>'.__('Leave blank for none', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="round_thumb_corner_size" id="round_thumb_corner_size" class="feed-them-social-admin-input" value="" placeholder="3px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			
			
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
		 // Check the premium version contains these new settings othwerise the settings page will show errors	
			if (!file_exists($this->premium.'admin/vine-settings-fields.php')) {
						$output .= '<div class="error feed-them-social-admin-input-wrap" style="margin:0px;"><p>' . __( 'Warning: You will need to upgrade the Premium Version of FTS to at least 1.5.0 to see the new premium settings.', 'feed-them-social' ) . '</p></div>';
			}
			else {
			include($this->premium.'admin/vine-settings-fields.php');
			}
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('Hide Title and Text', 'feed-them-social'),
				__('Vine Logo Size', 'feed-them-social'),
				__('Hide Vine Logo', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
	}
	
			$output .= $this->generate_shortcode('updateTextArea_vine();', 'Vine Feed Shortcode', 'vine-final-shortcode');
			$output .= '</form>';
		$output .= '</div><!--/fts-vine-shortcode-form-->';
		return $output;
		
	}
	//**************************************************
	// Instagram Twitter Form.
	//**************************************************
	function fts_instagram_form($save_options = false) {
		if ($save_options) {
			$instagram_name_option = get_option('convert_instagram_username');
			$instagram_id_option = get_option('instagram_id');
			$pics_count_option = get_option('pics_count');
			$instagram_popup_option = get_option('instagram_popup_option');
			$instagram_load_more_option = get_option('instagram_load_more_option');
		}
		$output = '<div class="fts-instagram-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">';
			
			// Check to see if token is in place otherwise show a message letting person no what they need to do
			$instagramOptions = get_option('fts_instagram_custom_api_token') ? 'Yes' : 'No' ;
			$output .= isset($instagramOptions) && $instagramOptions !== 'No' ? ''. "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add an Instagram API Token to our <a href="admin.php?page=fts-instagram-feed-styles-submenu-page">Instagram Options</a> page before trying to view your feed.</div>'. "\n";
			// end custom message for requiring token
			
			// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
			if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
				// INSTAGRAM FEED TYPE
				$output .= '<h2>'.__('Instagram Shortcode Generator', 'feed-them-social').'</h2><div class="feed-them-social-admin-input-wrap instagram-gen-selection">';
				$output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
				$output .= '<select name="instagram-messages-selector" id="instagram-messages-selector" class="feed-them-social-admin-input">';
				$output .= '<option value="user">'.__('User Feed', 'feed-them-social').'</option>';
				$output .= '<option value="hashtag">'.__('Hashtag Feed', 'feed-them-social').'</option>';
				//$output .= '<option value="hashtag">Facebook Hashtag</option>';
				$output .= '</select>';
				$output .= '<div class="clear"></div>';
				$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			};
			$output .= '<div class="instagram-id-option-wrap">';
			$output .= '<h3>'.__('Convert Instagram Name to ID', 'feed-them-social').'</h3>';
		}
		$instagram_name_option = isset($instagram_name_option) ? $instagram_name_option : "";
		$instagram_id_option = isset($instagram_id_option) ? $instagram_id_option : "";
		$output .= '<div class="instructional-text">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">'.__('Instagram Name', 'feed-them-social').'</a> '.__('and paste it in the first input below', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap convert_instagram_username">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Instagram Name (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" id="convert_instagram_username" name="convert_instagram_username" class="feed-them-social-admin-input" value="'.$instagram_name_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		$output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="'.__('Convert Instagram Username', 'feed-them-social').'" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />';
		// ONLY THIS DIV IF ON OUR SETTINGS PAGE
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			$output .= '</div><!--instagram-id-option-wrap-->';
		};
		if ($save_options == false) {
			$output .= '</form>';
		}
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">';
		}
		$output .= '<div class="instructional-text instagram-user-option-text" style="margin-top:12px;">'.__('If you added your ID above and clicked convert, a number should appear in the input below, now continue.', 'feed-them-social').'</div>';
		$output .= '<div class="instructional-text instagram-hashtag-option-text" style="display:none;margin-top:12px;">'.__('Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-wrap instagram_name">';
		$output .= '<div class="feed-them-social-admin-input-label instagram-user-option-text">'.__('Instagram ID # (required)', 'feed-them-social').'</div>';
		$output .= '<div class="feed-them-social-admin-input-label instagram-hashtag-option-text" style="display:none;">'.__('Hashtag (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="instagram_id" id="instagram_id" class="feed-them-social-admin-input" value="'.$instagram_id_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		// Super Instagram Options
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
			$output .= '<div class="feed-them-social-admin-input-wrap">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Super Instagram Gallery', 'feed-them-social').'</div>';
			$output .= '<select id="instagram-custom-gallery" name="instagram-custom-gallery" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			$output .= '<div class="fts-super-instagram-options-wrap"><h3>'.__('Super Instagram Gallery Options', 'feed-them-social').'</h3><div class="instructional-text">'.__('View demos and', 'feed-them-social').' <a href="#">read more</a> '.__('on setup instructions.', 'feed-them-social').'</div>';
			$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Instagram Image Size', 'feed-them-social').'<br/><small>'.__('Max width is 640px', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-container-image-size" id="fts-slicker-instagram-container-image-size" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="clear"></div> </div>';
			$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Size of the Instagram Icon', 'feed-them-social').'<br/><small>'.__('Visible when you hover over photo', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-icon-center" id="fts-slicker-instagram-icon-center" class="feed-them-social-admin-input" value="65px" placeholder="">
           <div class="clear"></div></div>';
			$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('The space between photos', 'feed-them-social').'</div>
           <input type="text" name="fts-slicker-instagram-container-margin" id="fts-slicker-instagram-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
           <div class="clear"></div></div>';
			$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Hide Date, Likes and comments', 'feed-them-social').'<br/><small>'.__('Good for image sizes under 120px', 'feed-them-social').'</small></div>
       		 <select id="fts-slicker-instagram-container-hide-date-likes-comments" name="fts-slicker-instagram-container-hide-date-likes-comments" class="feed-them-social-admin-input">
        	  <option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
			$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Center Instagram Container', 'feed-them-social').'</div>
        	<select id="fts-slicker-instagram-container-position" name="fts-slicker-instagram-container-position" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select>
           <div class="clear"></div></div>';
			$output .= ' <div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Image Stacking Animation On', 'feed-them-social').'<br/><small>'.__('This happens when resizing browser', 'feed-them-social').'</small></div>
        	 <select id="fts-slicker-instagram-container-animation" name="fts-slicker-instagram-container-animation" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
										
										
				// INSTAGRAM HEIGHT OPTION
			$output .= '<div class="feed-them-social-admin-input-wrap instagram_fixed_height_option">';
			$output .= '<div class="feed-them-social-admin-input-label">'.__('Instagram Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
			$output .= '<input type="text" name="instagram_page_height" id="instagram_page_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!--/feed-them-social-admin-input-wrap-->';


			$output .= '</div><!--fts-super-instagram-options-wrap-->';
		 
			

		}
			
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			
			include($this->premium.'admin/instagram-settings-fields.php');
			
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('# of Pics (default 6)', 'feed-them-social'),
				__('Display Photos in Popup', 'feed-them-social'),
				__('Load More Posts', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
		}
		if ($save_options == false) {
			$output .= $this->generate_shortcode('updateTextArea_instagram();', 'Instagram Feed Shortcode', 'instagram-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn instagram-submit" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-instagram-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Youtube Form.
	//**************************************************
	function fts_youtube_form($save_options = false) {
		if ($save_options) {
			$youtube_name_option = get_option('youtube_name');
			$youtube_vid_count_option = get_option('youtube_vid_count');
			$youtube_columns_option = get_option('youtube_columns');
			$youtube_first_video_option = get_option('youtube_first_video');
		}
		$output = '<div class="fts-youtube-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">';
			
				// Check to see if token is in place otherwise show a message letting person no what they need to do
			$youtubeOptions = get_option('youtube_custom_api_token') ?'Yes' :'No' ;
			$output .= isset($youtubeOptions) && $youtubeOptions !== 'No' ? ''. "\n" : '<div class="feed-them-social-admin-input-wrap fts-required-token-message">Please add a YouTube API Key to our <a href="admin.php?page=fts-youtube-feed-styles-submenu-page">YouTube Options</a> page before trying to view your feed.</div>'. "\n";
			// end custom message for requiring token
			
			$output .= '<h2>'.__('YouTube Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$output .= '<div class="instructional-text">'.__('You must copy your YouTube ', 'feed-them-social').' <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">'.__('Username, Channel ID and or Playlist ID', 'feed-them-social').'</a> '.__('and paste it below.', 'feed-them-social').'</div>';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/youtube-settings-fields.php');
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('YouTube Name', 'feed-them-social'),
				__('# of videos', 'feed-them-social'),
				__('# of videos in each row', 'feed-them-social'),
				__('Display First video full size', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
			$output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:inline-block; text-decoration:none !important;">'.__('Click to see Premium Version', 'feed-them-social').'</a>';
			$output .= '</form>';
		}
		$output .= '</div><!--/fts-youtube-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Pinterest Form.
	//**************************************************
	function fts_pinterest_form($save_options = false) {
		if ($save_options) {
			$pinterest_name_option = get_option('pinterest_name');
			$boards_count_option = get_option('boards_count');
		}
		$output = '<div class="fts-pinterest-shortcode-form">';
		if ($save_options == false) {
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">';
		}
		// Pinterest FEED TYPE
		$output .= '<h2>'.__('Pinterest Shortcode Generator', 'feed-them-social').'</h2><div class="feed-them-social-admin-input-wrap pinterest-gen-selection">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
		$output .= '<select name="pinterest-messages-selector" id="pinterest-messages-selector" class="feed-them-social-admin-input">';
		$output .= '<option value="boards_list">'.__('Board List', 'feed-them-social').'</option>';
		$output .= '<option value="single_board_pins">'.__('Pins From a Specific Board', 'feed-them-social').'</option>';
		$output .= '<option value="pins_from_user">'.__('Latest Pins from a User', 'feed-them-social').'</option>';
		$output .= '</select>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		$output .= '<h3>'.__('Pinterest Feed', 'feed-them-social').'</h3><div class="instructional-text pinterest-name-text">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">'.__('Pinterest Name', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		$output .= '<div class="instructional-text pinterest-board-and-name-text" style="display:none;">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">'.__('Pinterest and Board Name', 'feed-them-social').'</a> '.__('and paste them below.', 'feed-them-social').'</div>';
		$pinterest_name_option = isset($pinterest_name_option) ? $pinterest_name_option : "";
		$boards_count_option = isset($boards_count_option) ? $boards_count_option : "";
		$output .= '<div class="feed-them-social-admin-input-wrap pinterest_name">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Pinterest Username (required)', 'feed-them-social').'</div>';
		$output .= '<input type="text" name="pinterest_name" id="pinterest_name" class="feed-them-social-admin-input" value="'.$pinterest_name_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		$output .= '<div class="feed-them-social-admin-input-wrap board-name" style="display:none;">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Pinterest Board Name (required)', 'feed-them-premium').'</div>';
		$output .= '<input type="text" name="pinterest_board_name" id="pinterest_board_name" class="feed-them-social-admin-input" value="'.$pinterest_name_option.'" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/pinterest-settings-fields.php');
		}
		else {
			//Create Need Premium Fields
			$fields = array(
				__('# of Boards (default 6)', 'feed-them-social'),
				__('# of Pins (default 6)', 'feed-them-social'),
			);
			$output .= $this->need_fts_premium_fields($fields);
		}
		if (!$save_options) {
			$output .= $this->generate_shortcode('updateTextArea_pinterest();', ''.__('Pinterest Feed Shortcode', 'feed-them-social').'', 'pinterest-final-shortcode');
			$output .= '</form>';
		}
		else {
			$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
		}
		$output .= '</div><!--/fts-pinterest-shortcode-form-->';
		return $output;
	}
	//**************************************************
	// Generate Shorecode Button and I<?phpnput for FTS settings Page.
	//**************************************************
	function generate_shortcode($onclick, $label, $input_class) {
		$output = '<input type="button" class="feed-them-social-admin-submit-btn" value="'.__('Generate Shortcode', 'feed-them-social').'" onclick="'.$onclick.'" tabindex="4" style="margin-right:1em;" />';
		$output .= '<div class="feed-them-social-admin-input-wrap final-shortcode-textarea">';
		$output .= '<h4>'.__('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social').'</h4>';
		$output .= '<div class="feed-them-social-admin-input-label">'.$label.'</div>';
		$output .= '<input class="copyme '.$input_class.' feed-them-social-admin-input" value="" />';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		return $output;
	}
	//**************************************************
	// Generate Get Json (includes MultiCurl)
	//**************************************************
	function fts_get_feed_json($feeds_mulit_data) {
			// data to be returned
			$response = array();
			$curl_success = true;
			if (is_callable('curl_init')) {
				if(is_array($feeds_mulit_data)){
					// array of curl handles
					$curly = array();
					// multi handle
					$mh = curl_multi_init();
					// loop through $data and create curl handles
					// then add them to the multi-handle
					foreach ($feeds_mulit_data as $id => $d) {
						$curly[$id] = curl_init();
						$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
						curl_setopt($curly[$id], CURLOPT_URL,            $url);
						curl_setopt($curly[$id], CURLOPT_HEADER,         0);
						curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
						// post?
						if (is_array($d)) {
							if (!empty($d['post'])) {
								curl_setopt($curly[$id], CURLOPT_POST,       1);
								curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
							}
						}
						// extra options?
						if (!empty($options)) {
							curl_setopt_array($curly[$id], $options);
						}
						curl_multi_add_handle($mh, $curly[$id]);
					}
					// execute the handles
					$running = null;
					do {
						$curl_status = curl_multi_exec($mh, $running);
						// Check for errors
						$info = curl_multi_info_read($mh);
						if (false !== $info) {
							// Add connection info to info array:
							if (!$info['result']) {
								//$multi_info[(integer) $info['handle']]['error'] = 'OK';
							} else {
								$multi_info[(integer) $info['handle']]['error'] = curl_error($info['handle']);
								$curl_success = false;
							}
						}
					} while ($running > 0);
					// get content and remove handles
					foreach ($curly as $id => $c) {
						$response[$id] = curl_multi_getcontent($c);
						curl_multi_remove_handle($mh, $c);
					}
					curl_multi_close($mh);
				}//END Is_ARRAY
				//NOT ARRAY SINGLE CURL
				else{
					$ch = curl_init($feeds_mulit_data);
					curl_setopt_array($ch, array(
						CURLOPT_URL => $url,
					    CURLOPT_RETURNTRANSFER => true,
					    CURLOPT_HEADER => 0,
					    CURLOPT_POST => true,
					    CURLOPT_SSL_VERIFYPEER => false,
					    CURLOPT_SSL_VERIFYHOST => 0
					));
					$response = curl_exec($ch);
					curl_close($ch);
				}

			}
			//File_Get_Contents if Curl doesn't work
			if (!$curl_success && ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE) {
				foreach ($feeds_mulit_data as $id => $d) {
					$response[$id] = @file_get_contents($d);
				}
			} else {
				//If nothing else use wordpress http API
				if (!$curl_success && !class_exists( 'WP_Http' )) {
					include_once( ABSPATH . WPINC. '/class-http.php' );
					$wp_http_class = new WP_Http;
					foreach ($feeds_mulit_data as $id => $d) {
						$wp_http_result = $wp_http_class->request($d);
						$response[$id] = $wp_http_result['body'];
					}
				}
				//Do nothing if Curl was Successful
			}
				return $response;
	}
	//**************************************************
	// Create feed cache
	//**************************************************
	function fts_create_feed_cache($transient_name, $response) {
			set_transient('fts_'.$transient_name, $response, 900);
	}
	//**************************************************
	// fts_get_feed_cache
	//**************************************************
	function fts_get_feed_cache($transient_name) {
		$returned_cache_data = get_transient('fts_'.$transient_name);
		return $returned_cache_data;
	}
	//**************************************************
	// fts_check_feed_cache_exists
	//**************************************************
	function fts_check_feed_cache_exists($transient_name) {
		if(false === ($special_query_results = get_transient('fts_'.$transient_name))){
			return false;
		}
		return true;
	}
	//**************************************************
	// this function is being called from the twitter feed it calls the ajax in this case.
	//**************************************************
	function fts_clear_cache_ajax() {
		global $wpdb;
		$not_expired= $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%'));
		$expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%'));
		wp_reset_query();
		return;
	} // end of my_ajax_callback()
	//**************************************************
	// Clear Cache Folder.
	//**************************************************
	function feed_them_clear_cache() {
		global $wpdb;
		$not_expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_fts_%'));
		$expired = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s ", '_transient_timeout_fts_%'));
		wp_reset_query();
		return 'Cache for all FTS Feeds cleared!';
	}	
	//**************************************************
	// Create our custom menu in the admin bar.
	//**************************************************
	function fts_admin_bar_menu() {
		global $wp_admin_bar;
		isset($ftsDevModeCache) ? $ftsDevModeCache : "";
		isset($ftsAdminBarMenu) ? $ftsAdminBarMenu : "";
		$ftsAdminBarMenu = get_option('fts_admin_bar_menu');
		$ftsDevModeCache = get_option('fts_clear_cache_developer_mode');
		if ( !is_super_admin() || !is_admin_bar_showing() || $ftsAdminBarMenu == 'hide-admin-bar-menu')
			return;
		$wp_admin_bar->add_menu( array(
				'id' => 'feed_them_social_admin_bar',
				'title' => __( 'Feed Them Social', 'feed-them-social'),
				'href' => FALSE ) );
		if ($ftsDevModeCache == '1') {
			$wp_admin_bar->add_menu( array(
					'id' => 'feed_them_social_admin_bar_clear_cache',
					'parent' => 'feed_them_social_admin_bar',
					'title' => __( 'Cache clears on page refresh now', 'feed-them-social'),
					'href' => FALSE )
			);
		}
		else {
			$wp_admin_bar->add_menu(
				array(
					'id' => 'feed_them_social_admin_bar_clear_cache',
					'parent' => 'feed_them_social_admin_bar',
					'title' => __( 'Clear Cache', 'feed-them-social'),
					'href' => '#' )
			);
		}
		$wp_admin_bar->add_menu( array(
				'id' => 'feed_them_social_admin_bar_settings',
				'parent' => 'feed_them_social_admin_bar',
				'title' => __( 'Settings', 'feed-them-social'),
				'href' => admin_url( 'admin.php?page=feed-them-settings-page') )
		);
	}
	function xml_json_parse($url) {
		$url_to_get['url'] = $url;
        $fileContents_returned = $this->fts_get_feed_json($url_to_get);
        $fileContents = $fileContents_returned['url'];
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);

        return $json;
    }

	//**************************************************
	// Create date format like fb and twitter
	//**************************************************
	// Thanks: http://php.quicoto.com/how-to-calculate-relative-time-like-facebook/
function fts_ago( $timestamp ){
				// not setting isset'ing anything because you have to save the settings page to even enable this feature
	   $fts_language_second = get_option('fts_language_second');
				if ( empty($fts_language_second) ) $fts_language_second = 'second';
    $fts_language_seconds = get_option('fts_language_seconds');
				if ( empty($fts_language_seconds) ) $fts_language_seconds = 'seconds';
    $fts_language_minute = get_option('fts_language_minute');
				if ( empty($fts_language_minute) ) $fts_language_minute = 'minute';
    $fts_language_minutes = get_option('fts_language_minutes');
				if ( empty($fts_language_minute) ) $fts_language_minute = 'minutes';
    $fts_language_hour = get_option('fts_language_hour');
				if ( empty($fts_language_hour) ) $fts_language_hour = 'hour';
    $fts_language_hours = get_option('fts_language_hours');
				if ( empty($fts_language_hours) ) $fts_language_hours = 'hours';
    $fts_language_day = get_option('fts_language_day');
				if ( empty($fts_language_day) ) $fts_language_day = 'day';
    $fts_language_days = get_option('fts_language_days');
				if ( empty($fts_language_days) ) $fts_language_days = 'days';
    $fts_language_week = get_option('fts_language_week');
				if ( empty($fts_language_week) ) $fts_language_week = 'week';
    $fts_language_weeks = get_option('fts_language_weeks');
				if ( empty($fts_language_weeks) ) $fts_language_weeks = 'weeks';
    $fts_language_month = get_option('fts_language_month');
				if ( empty($fts_language_month) ) $fts_language_month = 'month';
    $fts_language_months = get_option('fts_language_months');
				if ( empty($fts_language_months) ) $fts_language_months = 'months';
    $fts_language_year = get_option('fts_language_year');
				if ( empty($fts_language_year) ) $fts_language_year = 'year';
    $fts_language_years = get_option('fts_language_years');
				if ( empty($fts_language_years) ) $fts_language_years = 'years';
    $fts_language_ago = get_option('fts_language_ago');
				if ( empty($fts_language_ago) ) $fts_language_ago = 'ago';
												
				$difference = time() - $timestamp;
				//	$periods = array( "sec", "min", "hour", "day", "week", "month", "years", "decade" );
				$periods = array( $fts_language_second, $fts_language_minute, $fts_language_hour, $fts_language_day, $fts_language_week, $fts_language_month, $fts_language_year, "decade" );
				$periods_plural = array($fts_language_seconds, $fts_language_minutes, $fts_language_hours, $fts_language_days, $fts_language_weeks, $fts_language_months, $fts_language_years, "decades");
				
						if( !is_numeric( $timestamp ) ){
								$timestamp = strtotime( $timestamp );
								if( !is_numeric( $timestamp ) ){
									return "";
								}
							}
							$difference = time() - $timestamp;
								// Customize in your own language. Why thank-you I will.
							$lengths = array( "60","60","24","7","4.35","12","10");
							
							if ($difference > 0) { // this was in the past
								$ending = $fts_language_ago;
							}else { // this was in the future
								$difference = -$difference;
								//not doing dates in the future for posts
								$ending = "to go";
							}
							for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
																$difference /= $lengths[$j];
												}
												
												$difference = round($difference);
												
												if($difference != 1) {
																$periods[$j] = $periods_plural[$j];
												}
							$text = "$difference $periods[$j] $ending";
							return $text;
				}
}//END Class
new feed_them_social_functions();
?>