<?php
namespace feedthemsocial;
class FTS_facebook_options_page {
	function __construct() {
	}
	//**************************************************
	// Facebook Options Page
	//**************************************************
	function feed_them_facebook_options_page() {
		$fts_functions = new feed_them_social_functions();
?>

<div class="feed-them-social-admin-wrap">
<h1>
<?php _e('Facebook Feed Options', 'feed-them-social'); ?>
</h1>
<div class="use-of-plugin">
<?php _e('Change the language, color and more for your facebook feed using the options below.', 'feed-them-social'); ?>
</div>
<!-- custom option for padding -->
<form method="post" class="fts-facebook-feed-options-form" action="options.php">
<br/>
<?php // get our registered settings from the fts functions
		settings_fields('fts-facebook-feed-style-options');
		//Language select
		$fb_language = get_option('fb_language', 'en_US');
		//share button
		$fb_show_follow_btn = get_option('fb_show_follow_btn');
		$fb_show_follow_btn_where = get_option('fb_show_follow_btn_where');
		$fb_show_follow_btn_profile_pic = get_option('fb_show_follow_btn_profile_pic');
		$fb_like_btn_color = get_option('fb_like_btn_color', 'light');
		$fb_hide_shared_by_etc_text = get_option('fb_hide_shared_by_etc_text');
		$fb_hide_images_in_posts = get_option('fb_hide_images_in_posts');

		$lang_options_array = json_decode($fts_functions->xml_json_parse('https://www.facebook.com/translations/FacebookLocales.xml'));
		//echo'<pre>';
		// print_r($lang_options_array);
		//echo'</pre>';

?>
<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page" style="padding-top:0; border:none;">
<h3>
<?php _e('Language Options', 'feed-them-social'); ?>
</h3>
<?php _e('You must have your Facebook API Token saved at the bottom of this page before this feature will work. This option will translate the FB Titles and Like Button or Box Text. It will not tranlate your actual post. To tranlate the Feed Them Social parts of this plugin just set your language on the <a href="options-general.php" target="_blank">wordpress settings</a> page. If would like to help translate please visit our', 'feed-them-social'); ?>
<a href="http://glotpress.slickremix.com/projects" target="_blank">GlottPress</a>. </div>
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Language For Facebook Feeds', 'feed-them-social'); ?>
</div>
<select name="fb_language" id="fb-lang-btn" class="feed-them-social-admin-input">
<option value="en_US">
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<?php
		foreach ($lang_options_array->locale as $language ) {
			echo'<option '.selected($fb_language, $language->codes->code->standard->representation, true ).' value="'.$language->codes->code->standard->representation.'">'.$language->englishName.'</option>';
		}
?>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page">
<h3>
<?php _e('Like Button or Box Options', 'feed-them-social'); ?>
</h3>
</div>
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Show Follow Button', 'feed-them-social'); ?>
</div>
<select name="fb_show_follow_btn" id="fb-show-follow-btn" class="feed-them-social-admin-input">
<option>
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn, 'dont-display', false ) ?> value="dont-display">
<?php _e('Don\'t Display a Button', 'feed-them-social'); ?>
</option>
<optgroup label="Like Box">
<option <?php echo selected($fb_show_follow_btn, 'like-box', false ) ?> value="like-box">
<?php _e('Like Box', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn, 'like-box-faces', false ) ?> value="like-box-faces">
<?php _e('Like Box with Faces', 'feed-them-social'); ?>
</option>
</optgroup>
<optgroup label="Like Button">
<option <?php echo selected($fb_show_follow_btn, 'like-button', false ) ?> value="like-button">
<?php _e('Like Button', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn, 'like-button-share', false ) ?> value="like-button-share">
<?php _e('Like Button and Share Button', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn, 'like-button-faces', false ) ?> value="like-button-faces">
<?php _e('Like Button with Faces', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn, 'like-button-share-faces', false ) ?> value="like-button-share-faces">
<?php _e('Like Button and Share Button with Faces', 'feed-them-social'); ?>
</option>
</optgroup>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap" style="display:none">
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Show Profile Icon next to social option above', 'feed-them-social'); ?>
</div>
<select name="fb_show_follow_like_box_cover" id="fb-show-follow-like-box-cover" class="feed-them-social-admin-input">
<option>
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn_profile_pic, 'fb_like_box_cover-yes', false ) ?> value="fb_like_box_cover-yes">
<?php _e('Display Cover Photo in Like Box', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn_profile_pic, 'fb_like_box_cover-no', false ) ?> value="fb_like_box_cover-no">
<?php _e('Hide Cover Photo in Like Box', 'feed-them-social'); ?>
</option>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Like Button Color', 'feed-them-social'); ?>
</div>
<select name="fb_like_btn_color" id="fb-like-btn-color" class="feed-them-social-admin-input">
<option value="light">
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_like_btn_color, 'light', false ) ?> value="light">
<?php _e('Light', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_like_btn_color, 'dark', false ) ?> value="dark">
<?php _e('Dark', 'feed-them-social'); ?>
</option>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Placement of the Button(s)', 'feed-them-social'); ?>
</div>
<select name="fb_show_follow_btn_where" id="fb-show-follow-btn-where" class="feed-them-social-admin-input">
<option value="">
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn_where, 'fb-like-top-above-title', false ) ?> value="fb-like-top-above-title">
<?php _e('Show Top of Feed Above Title', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn_where, 'fb-like-top-below-title', false ) ?> value="fb-like-top-below-title">
<?php _e('Show Top of Feed Below Title', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_show_follow_btn_where, 'fb-like-below', false ) ?> value="fb-like-below">
<?php _e('Show Botton of Feed', 'feed-them-social'); ?>
</option>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->


<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Facebook APP ID<br/><small>View Step 3 to <a href="http://www.slickremix.com/docs/create-facebook-app-id-or-user-token" target="_blank">get APP ID</a>.</small>', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_app_ID" class="feed-them-social-admin-input"  id="fb-app-ID" value="<?php echo get_option('fb_app_ID');?>"/>
<div class="clear"></div>
</div>
<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page" style="margin-top:0;">
<h3>
<?php _e('Style Options', 'feed-them-social'); ?>
</h3>
</div>


<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Text after your FB name <br/><small>ie* Shared by or New Photo Added etc.</small>', 'feed-them-social'); ?>
</div>
<select name="fb_hide_shared_by_etc_text" id="fb_hide_shared_by_etc_text" class="feed-them-social-admin-input">
<option value="">
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_hide_shared_by_etc_text, 'no', false ) ?> value="no">
<?php _e('No', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_hide_shared_by_etc_text, 'yes', false ) ?> value="yes">
<?php _e('Yes', 'feed-them-social'); ?>
</option>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
<?php _e('Hide Images in Posts', 'feed-them-social'); ?>
</div>
<select name="fb_hide_images_in_posts" id="fb_hide_images_in_posts" class="feed-them-social-admin-input">
<option value="">
<?php _e('Please Select Option', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_hide_images_in_posts, 'no', false ) ?> value="no">
<?php _e('No', 'feed-them-social'); ?>
</option>
<option <?php echo selected($fb_hide_images_in_posts, 'yes', false ) ?> value="yes">
<?php _e('Yes', 'feed-them-social'); ?>
</option>
</select>
<div class="clear"></div>
</div>
<!--/fts-twitter-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
<?php _e('Max-width for Images & Videos', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_max_image_width" class="feed-them-social-admin-input"  placeholder="500px" value="<?php echo get_option('fb_max_image_width');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
<?php _e('Feed Header Extra Text Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_header_extra_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-text-color-input" placeholder="#222" value="<?php echo get_option('fb_header_extra_text_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-text-color-label">
<?php _e('Feed Text Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-text-color-input" placeholder="#222" value="<?php echo get_option('fb_text_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-link-color-label">
<?php _e('Feed Link Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_link_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-link-color-input" placeholder="#222" value="<?php echo get_option('fb_link_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-link-color-hover-label">
<?php _e('Feed Link Color Hover', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_link_color_hover" class="feed-them-social-admin-input fb-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('fb_link_color_hover');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-feed-width-label">
<?php _e('Feed Width', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_feed_width" class="feed-them-social-admin-input fb-feed-width-input"  id="fb-feed-width-input" placeholder="500px" value="<?php echo get_option('fb_feed_width');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-feed-margin-label">
<?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_feed_margin" class="feed-them-social-admin-input fb-feed-margin-input"  id="fb-feed-margin-input" placeholder="10px" value="<?php echo get_option('fb_feed_margin');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-feed-padding-label">
<?php _e('Feed Padding', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_feed_padding" class="feed-them-social-admin-input fb-feed-padding-input"  id="fb-feed-padding-input" placeholder="10px" value="<?php echo get_option('fb_feed_padding');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-feed-background-color-label">
<?php _e('Feed Background Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_feed_background_color" class="feed-them-social-admin-input fb-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_feed_background_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label">
<?php _e('Feed Grid Posts Background Color (Grid style feeds ONLY)', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_grid_posts_background_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label">
<?php _e('Feed Border Bottom Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fb_border_bottom_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page">
<h3>
<?php _e('Event Style Options', 'feed-them-social'); ?>
</h3>
<?php _e('The styles above still apply, these are just some extra options for the Event List feed.', 'feed-them-social'); ?>
</div>
<div class="feed-them-social-admin-input-label fb-events-title-color-label">
<?php _e('Events Feed: Title Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_events_title_color" class="feed-them-social-admin-input fb-events-title-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-events-title-color-input" placeholder="#ddd" value="<?php echo get_option('fb_events_title_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fb-events-title-size-label">
<?php _e('Events Feed: Title Size', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_events_title_size" class="feed-them-social-admin-input fb-events-title-size"  id="fb-events-title-color-input" placeholder="20px" value="<?php echo get_option('fb_events_title_size');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
<?php _e('Events Feed: Map Link Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_events_map_link_color" class="feed-them-social-admin-input fb-events-map-link-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-events-map-link-color-input" placeholder="#ddd" value="<?php echo get_option('fb_events_map_link_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page" style="margin-bottom:0px;">
<h3>
<?php _e('Facebook API Token', 'feed-them-social'); ?>
</h3>
<?php _e('Facebook App Token works for all facebook feeds. Not required to make the feed work BUT an APP Token will stop the feed from returning the error, Are you sure this is a Facebook ID... See how to <a href="http://www.slickremix.com/docs/create-facebook-app-id-or-user-token" target="_blank">Get APP Token or Extended User Token</a>.', 'feed-them-social'); ?>
</div>
<?php
		$test_app_token_id = get_option('fts_facebook_custom_api_token');
		$test_app_token_id_biz = get_option('fts_facebook_custom_api_token_biz');
		if (!empty($test_app_token_id) || !empty($test_app_token_id_biz)) {
			$fts_fb_access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
			$test_app_token_URL = array(
				'app_token_id' => 'https://graph.facebook.com/debug_token?input_token='.$test_app_token_id.'&access_token='.$test_app_token_id
				// 'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=705020102908771&client_secret=70166128c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjLG2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'
			);
			$test_app_token_URL_biz = array(
				'app_token_id_biz' => 'https://graph.facebook.com/debug_token?input_token='.$test_app_token_id_biz.'&access_token='.$test_app_token_id_biz
				// 'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=705020102908771&client_secret=70166128c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjLG2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'
			);

			//Test App ID
			// Leave these for reference:
			// App token for FTS APP2: 358962200939086|lyXQ5-zqXjvYSIgEf8mEhE9gZ_M
			// App token for FTS APP3: 705020102908771|rdaGxW9NK2caHCtFrulCZwJNPyY
			$test_app_token_response = $fts_functions->fts_get_feed_json($test_app_token_URL);
			$test_app_token_response = json_decode($test_app_token_response['app_token_id']);


			$test_app_token_response_biz = $fts_functions->fts_get_feed_json($test_app_token_URL_biz);
			$test_app_token_response_biz = json_decode($test_app_token_response_biz['app_token_id_biz']);
		}


?>
<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
<?php _e('APP Token', 'feed-them-social'); ?>
</div>
<input type="text" name="fts_facebook_custom_api_token" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_facebook_custom_api_token');?>"/>
<div class="clear"></div>
</div>
<?php if (!empty($test_app_token_response)) {
			if (isset($test_app_token_response->data->is_valid)) {
				echo'<div class="fts-successful-api-token">'. __('Your APP Token is working!', 'feed-them-social').'</div>';
			}
			if (isset($test_app_token_response->data->error->message) || isset($test_app_token_response->error->message)) {
				if (isset($test_app_token_response->data->error->message)) {
					echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response->data->error->message.'</div>';
				}
				if (isset($test_app_token_response->error->message)) {
					echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response->error->message.'</div>';
				}

			}

		} else {
			echo'<div class="fts-successful-api-token">'. __('You are using our Default APP Token.', 'feed-them-social').'</div>';
		}
?>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<?php if(is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) { ?>
<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page">
<h3>
<?php _e('Reviews Style Options', 'feed-them-social'); ?>
</h3>
<?php _e('The styles above still apply, these are just some extra options for the Reviews List feed.', 'feed-them-social'); ?>
</div>
<div class="feed-them-social-admin-input-label fb-events-title-color-label">
<?php _e('Stars Background Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_reviews_backg_color" class="feed-them-social-admin-input fb-reviews-backg-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-reviews-backg-color" placeholder="#4791FF" value="<?php echo get_option('fb_reviews_backg_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="feed-them-social-admin-input-label fb-events-map-link-color-label">
<?php _e('Stars Text Color', 'feed-them-social'); ?>
</div>
<input type="text" name="fb_reviews_text_color" class="feed-them-social-admin-input fb-reviews-text-color color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-reviews-text-color" placeholder="#fff" value="<?php echo get_option('fb_reviews_text_color');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
<div class="fts-title-description-settings-page" style="margin-bottom:0px;">
<h3>
<?php _e('Facebook Page Reviews Access Token', 'feed-them-social'); ?>
</h3>
<?php _e('This Facebook Access Token works for the Reviews feed only. <a href="http://www.slickremix.com/facebook-never-expiring-page-token" target="_blank">Get your Page Access Token here</a>.', 'feed-them-social'); ?>
</div>
<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
<?php _e('Page Reviews Access Token', 'feed-them-social'); ?>
</div>
<input type="text" name="fts_facebook_custom_api_token_biz" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token_biz" value="<?php echo get_option('fts_facebook_custom_api_token_biz');?>"/>
<div class="clear"></div>
</div>
<!--/fts-facebook-feed-styles-input-wrap-->

<?php if (!empty($test_app_token_response_biz)) {
				if (isset($test_app_token_response_biz->data->is_valid)) {
					echo'<div class="fts-successful-api-token">'. __('Your Reviews Page Access Token is working!', 'feed-them-social').'</div>';
				}
				if (isset($test_app_token_response_biz->data->error->message) || isset($test_app_token_response_biz->error->message)) {
					if (isset($test_app_token_response_biz->data->error->message)) {
						echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response_biz->data->error->message.'</div>';
					}
					if (isset($test_app_token_response_biz->error->message)) {
						echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response_biz->error->message.'</div>';
					}

				}

			} else {
				echo'<div class="fts-successful-api-token">'. __('You are using our Default APP Token.', 'feed-them-social').'</div>';
			}
		}
?>
<div class="clear"></div>
<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
</form>
<div class="clear"></div>
<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
<!--/feed-them-social-admin-wrap-->

<?php }
}//END Class