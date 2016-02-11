<?php
function themeum_startup_idea_settings() {

	$current = $tab_data = '';	

	if( isset($_GET['tab']) ){ $tab_data = $_GET['tab']; }

	if( ( $tab_data == '' ) ){
		$current = 'general';	
	}else{
		$current = $tab_data;	
	}
	
    $tabs = array( 
    	'general' 	=> 'General Settings', 
    	'paypal' 	=> 'Paypal Settings', 
    	'stripe' 	=> 'Stripe Settings',
    	'checkout' 	=> 'Checkout Setting ',
    	); 
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?post_type=investment&page=themeum_startup_idea_settings&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
    ?>
    <form id="themeum-startup-idea-options" role="form" method="post" action="options.php">

        <?php settings_fields('themeum_startup_idea_options'); ?>

        
        <?php 
	        if( ( $tab_data == '' ) || ( $tab_data == 'general' ) ){ 
	        	echo '<div style="display:block;">'; 
	        }else{ 
	        	echo '<div style="display:none;">';
	        }
        ?>
        
        <h2><?php _e('General Settings', 'themeum-startup-idea'); ?></h2>
        <table class="form-table">
        	<tbody>

        		<tr>
        			<th scope="row"><label for="paypal_curreny_code"><?php _e('Select The Currency', 'themeum-startup-idea'); ?></label></th>
        			<td>
        				<select id="paypal_curreny_code" name="paypal_curreny_code"> 
        					<?php $currency_code = get_option('paypal_curreny_code'); ?>
							<option <?php if( $currency_code == "AUD"){ echo 'selected'; }  ?> value="AUD"><?php _e('Australian Dollar($)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "BRL"){ echo 'selected'; }  ?> value="BRL"><?php _e('Brazilian Real(R$)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "CAD"){ echo 'selected'; }  ?> value="CAD"><?php _e('Canadian Dollar($)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "CZK"){ echo 'selected'; }  ?> value="CZK"><?php _e('Czech Koruna(Kč)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "DKK"){ echo 'selected'; }  ?> value="DKK"><?php _e('Danish Krone(kr.)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "EUR"){ echo 'selected'; }  ?> value="EUR"><?php _e('Euro(€)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "HKD"){ echo 'selected'; }  ?> value="HKD"><?php _e('Hong Kong Dollar(HK$)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "HUF"){ echo 'selected'; }  ?> value="HUF"><?php _e('Hungarian Forint(Ft)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "ILS"){ echo 'selected'; }  ?> value="ILS"><?php _e('Israeli New Sheqel(₪)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "JPY"){ echo 'selected'; }  ?> value="JPY"><?php _e('Japanese Yen(¥)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "MYR"){ echo 'selected'; }  ?> value="MYR"><?php _e('Malaysian Ringgit(RM)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "MXN"){ echo 'selected'; }  ?> value="MXN"><?php _e('Mexican Peso(Mex$)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "NOK"){ echo 'selected'; }  ?> value="NOK"><?php _e('Norwegian Krone(kr)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "NZD"){ echo 'selected'; }  ?> value="NZD"><?php _e('New Zealand Dollar($)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "PHP"){ echo 'selected'; }  ?> value="PHP"><?php _e('Philippine Peso(₱)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "PLN"){ echo 'selected'; }  ?> value="PLN"><?php _e('Polish Zloty(zł)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "GBP"){ echo 'selected'; }  ?> value="GBP"><?php _e('Pound Sterling(£)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "RUB"){ echo 'selected'; }  ?> value="RUB"><?php _e('Russian Ruble(₽)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "SGD"){ echo 'selected'; }  ?> value="SGD"><?php _e('Singapore Dollar($)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "SEK"){ echo 'selected'; }  ?> value="SEK"><?php _e('Swedish Krona(kr)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "CHF"){ echo 'selected'; }  ?> value="CHF"><?php _e('Swiss Franc(CHF)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "TWD"){ echo 'selected'; }  ?> value="TWD"><?php _e('Taiwan New Dollar(角)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "THB"){ echo 'selected'; }  ?> value="THB"><?php _e('Thai Baht(฿)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "TRY"){ echo 'selected'; }  ?> value="TRY"><?php _e('Turkish Lira(TRY)', 'themeum-startup-idea'); ?></option>
							<option <?php if( $currency_code == "USD"){ echo 'selected'; }  ?> value="USD"><?php _e('U.S. Dollar($)', 'themeum-startup-idea'); ?></option>
						</select>
						<p class="description"><small><?php _e('Paypal Support Currency List', 'themeum-startup-idea'); ?> <a target="_blank" href="https://goo.gl/v0lgrZ"><?php _e('Here', 'themeum-startup-idea'); ?></a>.</small></p>
					</td>
        		</tr>

        		<tr>
					<th><label for="donate_page_percentage"><?php _e('Project Commission','themeum-startup-idea'); ?></label></th>
					<td>
					<input type="text" id="donate_page_percentage" class="regular-text" name="donate_page_percentage" value="<?php echo esc_attr(get_option('donate_page_percentage')); ?>" />%
					<p class="description"><small>Set project fee in percentage.</small></p>
					</td>
				</tr>

				<!--Profile page setting -->
        		<tr>
					<th scope="row"><label for="profile_page_id"><?php _e('Dashboard Page','themeum-startup-idea'); ?></label></th>
					<td>
						<?php
						$profile_page_id = '<select name="profile_page_id" id="profile_page_id">';
						foreach ( get_all_page_ids() as $value) {
							$page_title_all = get_post($value);
							if( get_option('profile_page_id') == $value ){
								$profile_page_id .= '<option selected="selected" value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
							else{
								$profile_page_id .= '<option value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
						}
						$profile_page_id .= '</select>';

						echo $profile_page_id;
						?>
						<p class="description"><small><?php _e('Create a Page Using "Page My Account" Plugins and Then Select here.','themeum-startup-idea'); ?></small></p>
					</td>
				</tr>

				<!-- User Profile page setting -->
        		<tr>
					<th scope="row"><label for="user_profile_page_id"><?php _e('User profile Page','themeum-startup-idea'); ?></label></th>
					<td>
						<?php
						$user_profile_page_id = '<select name="user_profile_page_id" id="user_profile_page_id">';
						foreach ( get_all_page_ids() as $value) {
							$page_title_all = get_post($value);
							if( get_option('user_profile_page_id') == $value ){
								$user_profile_page_id .= '<option selected="selected" value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
							else{
								$user_profile_page_id .= '<option value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
						}
						$user_profile_page_id .= '</select>';
						echo $user_profile_page_id;
						?>
						<p class="description"><small><?php _e('Create a Page Using "Page User Profile" Plugins and Then Select here.','themeum-startup-idea'); ?></small></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="paypal_payment_checkout_page_id"><?php _e('Checkout Page','themeum-startup-idea'); ?></label></th>
					<td>
						<?php
						$paypal_payment_checkout_page_id = '<select name="paypal_payment_checkout_page_id" id="paypal_payment_checkout_page_id">';
						foreach ( get_all_page_ids() as $value) {
							$page_title_all = get_post($value);
							if( get_option('paypal_payment_checkout_page_id') == $value ){
								$paypal_payment_checkout_page_id .= '<option selected="selected" value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
							else{
								$paypal_payment_checkout_page_id .= '<option value="'.$value.'">'.$page_title_all->post_title.'</option>';
							}
						}
						$paypal_payment_checkout_page_id .= '</select>';

						echo $paypal_payment_checkout_page_id;
						?>
						<p class="description"><small><?php _e('Create a Page Using "Page My Account" Plugins and Then Select here.','themeum-startup-idea'); ?></small></p>
					</td>
				</tr>

				
				<tr>
					<th scope="row"><label for="payment_success_page"><?php _e('Payment Success Return Page','themeum-startup-idea'); ?></label></th>
					<td>
						<?php
						$payment_success_page = '<select name="payment_success_page" id="payment_success_page">';
						foreach ( get_all_page_ids() as $value) {
							$page_title_all = get_post($value);
							if(get_option('payment_success_page')==get_page_link($value)){
								$payment_success_page .= '<option selected="selected" value="'.get_page_link($value).'">'.$page_title_all->post_title.'</option>';
							}
							else{
								$payment_success_page .= '<option value="'.get_page_link($value).'">'.$page_title_all->post_title.'</option>';
							}
						}
						$payment_success_page .= '</select>';

						echo $payment_success_page;
						?>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="payment_cancel_page"><?php _e('Payment Cancel Return Page','themeum-startup-idea'); ?></label></th>
					<td>
						<?php
						$payment_cancel_page = '<select name="payment_cancel_page" id="payment_cancel_page">';

						foreach ( get_all_page_ids() as $value) {
							$page_title_all = get_post($value);
							if(get_option('payment_cancel_page')==get_page_link($value)){
								$payment_cancel_page .= '<option selected="selected" value="'.get_page_link($value).'">'.$page_title_all->post_title.'</option>';
							}
							else{
								$payment_cancel_page .= '<option value="'.get_page_link($value).'">'.$page_title_all->post_title.'</option>';
							}
						}
						$payment_cancel_page .= '</select>';

						echo $payment_cancel_page;
						?>
					</td>
				</tr>
        	</tbody>
        </table>

        </div><!-- #Tab Display Settings On/Off -->


        
        <?php 
	        if( $tab_data == 'paypal' ){ 
	        	echo '<div style="display:block;">'; 
	        }else{ 
	        	echo '<div style="display:none;">';
	        }
        ?>
		<!-- Paypal Payment Settings -->
        <h2><?php _e('Paypal Settings', 'themeum-startup-idea'); ?></h2>
        <p><small><?php _e('PayPal standard works by sending customers to PayPal where they can enter their payment information.', 'themeum-startup-idea'); ?></small></p>
		<table class="form-table">
        	<tbody>

        		<tr>
        			<th scope="row"><label for="paypal_payment_type"><?php _e('Paypal Payment Type:', 'themeum-startup-idea'); ?></label></th>
        			<td>
        				<select  id="paypal_payment_type" name="paypal_payment_type">
							<?php $paypal_type = get_option('paypal_payment_type'); ?>
							<option value="standard" <?php if ( $paypal_type == 'standard' ) echo 'selected'; ?> ><?php _e('PayPal Standard','themeum-startup-idea'); ?></option>
							<option value="adaptive" <?php if ( $paypal_type == 'adaptive' ) echo 'selected'; ?> ><?php _e('PayPal Adaptive Payments ','themeum-startup-idea'); ?></option>
						</select>
        			</td>
        		</tr>

        		<tr>
        			<th scope="row"><label for="enable_paypal_payment"><?php _e('Enable/Disable', 'themeum-startup-idea'); ?></label></th>
        			<td>
        				<select  id="enable_paypal_payment" name="enable_paypal_payment">
							<?php $enable_paypal = get_option('enable_paypal_payment'); ?>
							<option value="0" <?php if ( $enable_paypal == '0' ) echo 'selected'; ?> ><?php _e('Disable','themeum-startup-idea'); ?></option>
							<option value="1" <?php if ( $enable_paypal == '1' ) echo 'selected'; ?> ><?php _e('Enable','themeum-startup-idea'); ?></option>
						</select>
        			</td>
        		</tr>

        		<tr valign="top">
					<th scope="row"><label for="paypal_email_address"><?php _e('Paypal Email Address','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_email_address" name="paypal_email_address" value="<?php echo esc_attr(get_option('paypal_email_address')); ?>" class="regular-text" /></td>
				</tr>

        		<tr>
					<th scope="row"><label for="paypal_mode"><?php _e('PayPal','themeum-startup-idea'); ?> </label></th>
					<td>
						<select  id="paypal_mode" name="paypal_mode">
							<?php $mode_paypal = get_option('paypal_mode'); ?>
						 	<option value="developer" <?php if ( $mode_paypal == 'developer' ) echo 'selected'; ?> ><?php _e('PayPal Sandbox','themeum-startup-idea'); ?></option>
							<option value="real" <?php if ( $mode_paypal == 'real' ) echo 'selected'; ?> ><?php _e('PayPal','themeum-startup-idea'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><strong><?php _e('PayPal Adaptive Payments Settings( Live )','themeum-startup-idea'); ?></strong></th>
					<td><hr></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_live_api_user_name"><?php _e('Paypal Live API User Name','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_live_api_user_name" name="paypal_live_api_user_name" value="<?php echo esc_attr(get_option('paypal_live_api_user_name')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_live_api_password"><?php _e('Paypal Live API Password','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_live_api_password" name="paypal_live_api_password" value="<?php echo esc_attr(get_option('paypal_live_api_password')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_live_api_signature"><?php _e('Paypal Live API Signature','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_live_api_signature" name="paypal_live_api_signature" value="<?php echo esc_attr(get_option('paypal_live_api_signature')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_live_app_id"><?php _e('Paypal Live App ID','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_live_app_id" name="paypal_live_app_id" value="<?php echo esc_attr(get_option('paypal_live_app_id')); ?>" class="regular-text" /></td>
				</tr>

				<tr>
					<th scope="row"><strong><?php _e('PayPal Adaptive Payments Settings( Sandbox )','themeum-startup-idea'); ?></strong></th>
					<td><hr></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_sandbox_api_user_name"><?php _e('Paypal Sandbox API User Name','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_sandbox_api_user_name" name="paypal_sandbox_api_user_name" value="<?php echo esc_attr(get_option('paypal_sandbox_api_user_name')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_sandbox_api_password"><?php _e('Paypal Sandbox API Password','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_sandbox_api_password" name="paypal_sandbox_api_password" value="<?php echo esc_attr(get_option('paypal_sandbox_api_password')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_sandbox_api_signature"><?php _e('Paypal Sandbox API Signature','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_sandbox_api_signature" name="paypal_sandbox_api_signature" value="<?php echo esc_attr(get_option('paypal_sandbox_api_signature')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="paypal_sandbox_app_id"><?php _e('Paypal Sandbox App ID','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="paypal_sandbox_app_id" name="paypal_sandbox_app_id" value="<?php echo esc_attr(get_option('paypal_sandbox_app_id')); ?>" class="regular-text" /></td>
				</tr>




			</tbody>
        </table>

        </div><!-- #Tab Display Settings On/Off -->



        <?php 
	        if( $tab_data == 'stripe' ){ 
	        	echo '<div style="display:block;">'; 
	        }else{ 
	        	echo '<div style="display:none;">';
	        }
        ?>
        <!-- Stipe Payment Settings -->
        <h2><?php _e('Stripe Settings', 'themeum-startup-idea'); ?></h2>
        <p><small><?php _e('Stripe allows user to pay in crad like visa, mastard card etc.', 'themeum-startup-idea'); ?></small></p>

        <table class="form-table">
        	<tbody>
        		<tr>
        			<th scope="row"><label for="enable_stripe_payment"><?php _e('Enable/Disable', 'themeum-startup-idea'); ?></label></th>
        			<td>
        				<select  id="enable_stripe_payment" name="enable_stripe_payment">
							<?php $enable_stripe = get_option('enable_stripe_payment'); ?>
							<option value="0" <?php if ( $enable_stripe == '0' ) echo 'selected'; ?> ><?php _e('Disable','themeum-startup-idea'); ?></option>
							<option value="1" <?php if ( $enable_stripe == '1' ) echo 'selected'; ?> ><?php _e('Enable','themeum-startup-idea'); ?></option>
						</select>
        			</td>
        		</tr>

        		<tr valign="top">
					<th scope="row"><label for="stripe_email_address"><?php _e('Stipe Email Address','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="stripe_email_address" name="stripe_email_address" value="<?php echo esc_attr(get_option('stripe_email_address')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="stripe_secret_key"><?php _e('Stipe Secret Key','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo esc_attr(get_option('stripe_secret_key')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="webhooks"><?php _e('Stipe Webhooks Receive URL','themeum-startup-idea'); ?></label></th>
					<td>
						<p style="color:#3E8CDD;"><?php echo plugins_url( 'payment/stripe-webhooks-receiver.php', dirname(__FILE__) ); ?></p>
						<small>This url for webhooks recevie. Set your webhooks recevie url in <a href="https://dashboard.stripe.com/account/webhooks" target="_blank">webhooks settings</a></small>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="stripe_site_name"><?php _e('Stipe Pop-up Form Site Name','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="stripe_site_name" name="stripe_site_name" value="<?php echo esc_attr(get_option('stripe_site_name')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="stripe_desc"><?php _e('Stipe Pop-up Form Description','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="stripe_desc" name="stripe_desc" value="<?php echo esc_attr(get_option('stripe_desc')); ?>" class="regular-text" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="stripe_logo"><?php _e('Stipe Pop-up Form Logo ( image url )','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="stripe_logo" name="stripe_logo" value="<?php echo esc_attr(get_option('stripe_logo')); ?>" class="regular-text" /></td>
				</tr>

			</tbody>
        </table>

        </div><!-- #Tab Display Settings On/Off -->


	
		<?php 
	        if( $tab_data == 'checkout' ){ 
	        	echo '<div style="display:block;">'; 
	        }else{ 
	        	echo '<div style="display:none;">';
	        }
        ?>
		<!-- Fund/Checkout Page Setting -->
		<h2><?php _e('Fund/Checkout Page Setting', 'themeum-startup-idea'); ?></h2>
		<p><small><?php _e('This is fund/checkout page content setting.', 'themeum-startup-idea'); ?></small></p>
		<!-- Donate page title and text -->
		<table class="form-table">
        	<tbody>
        		<tr valign="top">
					<th scope="row"><label for="default_payment"><?php _e('Default Payment System','themeum-startup-idea'); ?></label></th>
					<td>
						<?php $default_payment = esc_attr(get_option('default_payment')); ?>
						<input type="radio" name="default_payment" value="paypal" <?php if ( $default_payment == 'paypal' ) echo 'checked'; ?> /><span> Paypal Payment</span>
						<input type="radio" name="default_payment" value="stripe" <?php if ( $default_payment == 'stripe' ) echo 'checked'; ?> /><span> Stripe/Card Payment </span>
					</td>
				</tr>
				<tr>
					<th><label for="donate_page_notice_title"><?php _e('Fund/Checkout Page Notice Title','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="donate_page_notice_title" class="regular-text" name="donate_page_notice_title" value="<?php echo esc_attr(get_option('donate_page_notice_title')); ?>" /></td>
				</tr>
				<tr>
					<th><label for="donate_page_notice_content"><?php _e('Fund/Checkout Page Notice Content','themeum-startup-idea'); ?></label></th>
					<td><textarea id="donate_page_notice_content" name="donate_page_notice_content" rows="7" cols="50"><?php echo esc_attr(get_option('donate_page_notice_content')); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="donate_page_notice_text"><?php _e('Fund/Checkout Page 2nd Title','themeum-startup-idea'); ?></label></th>
					<td><input type="text" id="donate_page_notice_text" class="regular-text" name="donate_page_notice_text" value="<?php echo esc_attr(get_option('donate_page_notice_text')); ?>" /></td>
				</tr>

				<tr>
					<th><label for="donate_page_notice_text_content"><?php _e('Fund/Checkout Page 2nd Content','themeum-startup-idea'); ?></label></th>
					<td><textarea id="donate_page_notice_text_content" name="donate_page_notice_text_content" rows="7" cols="50"><?php echo esc_attr(get_option('donate_page_notice_text_content')); ?></textarea></td>
				</tr>
				
				<?php do_action('themeum_startup_idea_payment_method'); ?>

        	</tbody>
        </table>
        </div><!-- #Tab Display Settings On/Off -->

        <?php submit_button(); ?>
    </form>
    <?php
}



function register_themeum_startup_idea_settings()
{
	add_option( 'paypal_email_address', 'example@example.com');
	add_option( 'paypal_curreny_code', 'USD');
	add_option( 'paypal_mode', 'developer');
	add_option( 'payment_success_page', '');
	add_option( 'payment_cancel_page', '');
	add_option( 'enable_paypal_payment', '1');
	add_option( 'paypal_payment_checkout_page_id', '');
	add_option( 'profile_page_id', '');
	add_option( 'user_profile_page_id', '');
	add_option( 'stripe_site_name', 'StartUp Idea');
	add_option( 'stripe_desc', 'Fund this project');
	add_option( 'stripe_logo', 'https://d13yacurqjgara.cloudfront.net/assets/apple-touch-icon-precomposed-b1a20c2007538ac346fecc6ed06fd08a.png');
	add_option( 'donate_page_notice_title', 'IMPORTANT');
	add_option( 'donate_page_percentage', '5');
	add_option( 'donate_page_notice_content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna ');
	add_option( 'donate_page_notice_text', 'How to Fund');

	//Paypal Addaptive Payment Setting
	add_option( 'paypal_live_api_user_name','');
	add_option( 'paypal_live_api_password','');
	add_option( 'paypal_live_api_signature','');
	add_option( 'paypal_live_app_id','');
	add_option( 'paypal_sandbox_api_user_name','');
	add_option( 'paypal_sandbox_api_password','');
	add_option( 'paypal_sandbox_api_signature','');
	add_option( 'paypal_sandbox_app_id','');
	add_option( 'paypal_payment_type','standard');


	// Register seetings options
	register_setting( 'themeum_startup_idea_options', 'enable_paypal_payment');
	register_setting( 'themeum_startup_idea_options', 'paypal_payment_checkout_page_id');
	register_setting( 'themeum_startup_idea_options', 'profile_page_id');
	register_setting( 'themeum_startup_idea_options', 'user_profile_page_id');
	register_setting( 'themeum_startup_idea_options', 'paypal_email_address');
	register_setting( 'themeum_startup_idea_options', 'paypal_curreny_code');
	register_setting( 'themeum_startup_idea_options', 'paypal_mode');
	register_setting( 'themeum_startup_idea_options', 'payment_success_page');
	register_setting( 'themeum_startup_idea_options', 'payment_cancel_page');
	register_setting( 'themeum_startup_idea_options', 'enable_stripe_payment');
	register_setting( 'themeum_startup_idea_options', 'stripe_email_address');
	register_setting( 'themeum_startup_idea_options', 'stripe_secret_key');
	register_setting( 'themeum_startup_idea_options', 'stripe_site_name');
	register_setting( 'themeum_startup_idea_options', 'stripe_desc');
	register_setting( 'themeum_startup_idea_options', 'stripe_logo');
	register_setting( 'themeum_startup_idea_options', 'default_payment');
	register_setting( 'themeum_startup_idea_options', 'donate_page_notice_title');
	register_setting( 'themeum_startup_idea_options', 'donate_page_percentage');
	register_setting( 'themeum_startup_idea_options', 'donate_page_notice_content');
	register_setting( 'themeum_startup_idea_options', 'donate_page_notice_text');
	register_setting( 'themeum_startup_idea_options', 'donate_page_notice_text_content');

	//Paypal Addaptive Payment Setting
	register_setting( 'themeum_startup_idea_options', 'paypal_live_api_user_name');
	register_setting( 'themeum_startup_idea_options', 'paypal_live_api_password');
	register_setting( 'themeum_startup_idea_options', 'paypal_live_api_signature');
	register_setting( 'themeum_startup_idea_options', 'paypal_live_app_id');
	register_setting( 'themeum_startup_idea_options', 'paypal_sandbox_api_user_name');
	register_setting( 'themeum_startup_idea_options', 'paypal_sandbox_api_password');
	register_setting( 'themeum_startup_idea_options', 'paypal_sandbox_api_signature');
	register_setting( 'themeum_startup_idea_options', 'paypal_sandbox_app_id');
	register_setting( 'themeum_startup_idea_options', 'paypal_payment_type');
}

add_action( 'admin_init', 'register_themeum_startup_idea_settings' );




function themeum_startup_idea_withdraw() {
?>
<h1><?php _e('Withdraw Request','themeum-startup-idea') ?></h1>
<?php 
	global $wpdb;

	$range = 20;
	$limit = 'LIMIT 0,'.$range;
	if(isset($_GET['limit'])){
		if($_GET['limit']){
			$all = $range*$_GET['limit'];

			$limit = 'LIMIT '.( $all - $range ).', '.$all.' ';
		}
	}

	$result = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='%s' AND meta_value='%s' $limit",'thm_withdraw_request','yes'));
 ?>

<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<td><?php _e('Name','themeum-startup-idea') ?></td>
		<td><?php _e('Author','themeum-startup-idea') ?></td>
		<td><?php _e('Budget','themeum-startup-idea') ?></td>
		<td><?php _e('Comission','themeum-startup-idea') ?>(n%)</td>
		<td><?php _e('Action','themeum-startup-idea') ?></td>
	</tr>
	</thead>
	<tbody>
		 <?php 
			if(is_array($result)){
				
				foreach ($result as $value) {
					
					$funding_goal =  esc_attr(get_post_meta( $value->post_id ,'thm_funding_goal', true ));
					$percent = esc_attr(get_post_meta( $value->post_id, 'thm_percentage' , true));

					echo '<tr>';
					echo '<td>'.get_the_title( $value->post_id ).'</td>';
					echo '<td>'.get_the_author_meta( 'user_login', get_post_field( 'post_author', $value->post_id ) ).'</td>';
					echo '<td>'.$funding_goal.esc_attr(themeum_get_currency_symbol()).'</td>';

					$per = 0;
					if( ($funding_goal > 0)  && ( $percent > 0 ) ){
						$per = ($percent/100)*$funding_goal;
						}
					echo '<td>'.$per.' ('.$percent.'%)</td>';
					$confirm_url = basename($_SERVER['REQUEST_URI']);
					?>
					<td>
						<form name="confirm-submit-form" action="<?php admin_url("admin-ajax.php"); ?>" method="post" id="confirm-submit-form">
					        <input type="hidden" name="confirm-post-id" value="<?php echo $value->post_id; ?>">
					        <input type="hidden" id="redirect_url_confirm" name="uri" value="<?php echo $confirm_url; ?>">
					        <input type="hidden" name="project_confirm" value="1">
					        <button id="confirm-submit-form" class="button" type="submit"><?php echo __('Confirm','themeum-startup-idea'); ?></button>
					    </form>
					</td>
					<?php
					echo '</tr>';

				}
				
			}
		 ?>
	</tbody>
</table>
<?php 


$results = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='%s' AND meta_value='%s'",'thm_withdraw_request','yes'));
$loop = ceil(count($results)/$range);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link = explode('&limit=',$actual_link);
echo '<div class="tablenav"><div class="tablenav-pages"><span class="pagination-links">';
	for ($i=1; $i <= $loop; $i++) { 
		if( isset($_GET['limit']) ){
			if($i == $_GET['limit']){
				echo '<a class="actives" href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
			}else{
				echo '<a href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';	
			}
		}else{
			if($i == 1){
				echo '<a class="actives" href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
			}else{
				echo '<a href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
			}
				
		}
	}
echo '</span></div></div>';

}



function themeum_startup_idea_paid(){
?>
<h1><?php _e('Withdraw Done','themeum-startup-idea') ?></h1>
<?php 
	global $wpdb;
	
	$range = 20;
	$limit = 'LIMIT 0,'.$range;
	if(isset($_GET['limit'])){
		if($_GET['limit']){
			$all = $range*$_GET['limit'];

			$limit = 'LIMIT '.( $all - $range ).', '.$all.' ';
		}
	}

	$result =  $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='%s' AND meta_value='%s' $limit",'thm_withdraw_request','done'));
 ?>

<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<td><?php _e('Name','themeum-startup-idea') ?></td>
		<td><?php _e('Author','themeum-startup-idea') ?></td>
		<td><?php _e('Budget','themeum-startup-idea') ?></td>
		<td><?php _e('Comission','themeum-startup-idea') ?>(n%)</td>
		<td><?php _e('Status','themeum-startup-idea') ?></td>
	</tr>
	</thead>
	<tbody>
		 <?php 
			if(is_array($result)){
				
				foreach ($result as $value) {
					
					$funding_goal =  esc_attr(get_post_meta( $value->post_id ,'thm_funding_goal', true ));
					$percent = esc_attr(get_post_meta( $value->post_id, 'thm_percentage' , true));
					$status = esc_attr(get_post_meta( $value->post_id, 'thm_withdraw_request' , true));


					echo '<tr>';
					echo '<td>'.get_the_title( $value->post_id ).'</td>';
					echo '<td>'.get_the_author_meta( 'user_login', get_post_field( 'post_author', $value->post_id ) ).'</td>';
					echo '<td>'.$funding_goal.esc_attr(themeum_get_currency_symbol()).'</td>';

					$per = 0;
					if( ($funding_goal > 0)  && ( $percent > 0 ) ){
						$per = ($percent/100)*$funding_goal;
						}
					echo '<td>'.$per.' ('.$percent.'%)</td>';
					echo '<td>'.$status.'</td>';
					echo '</tr>';

				}
				
			}
		 ?>
	</tbody>
</table>
<?php	

	$results = $wpdb->get_results( $wpdb->prepare("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='%s' AND meta_value='%s'",'thm_withdraw_request','done'));
	$loop = ceil(count($results)/$range);
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_link = explode('&limit=',$actual_link);
	echo '<div class="tablenav"><div class="tablenav-pages"><span class="pagination-links">';
		for ($i=1; $i <= $loop; $i++) { 
			if( isset($_GET['limit']) ){
				if($i == $_GET['limit']){
					echo '<a class="actives" href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
				}else{
					echo '<a href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';	
				}
			}else{
				if($i == 1){
					echo '<a class="actives" href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
				}else{
					echo '<a href="'.$actual_link[0].'&limit='.$i.'">'.$i.'</a>';
				}
					
			}
		}
	echo '</span></div></div>';

}





// Add Menu in admin panel
function themeum_admin_menu(){
		add_menu_page( __( 'Startup Idea', 'themeum-startup-idea' ), __( 'Startup Settings', 'themeum-startup-idea' ), 'administrator', 'edit.php?post_type=investment', null, 'dashicons-feedback' );
		add_submenu_page( 'edit.php?post_type=investment', __( 'Themeum Settings', 'themeum-startup-idea' ), __( 'Settings', 'themeum-startup-idea' ), 'administrator', 'themeum_startup_idea_settings', 'themeum_startup_idea_settings' );
		add_submenu_page( 'edit.php?post_type=investment', __( 'Withdraw', 'themeum-startup-idea' ), __( 'Withdraw', 'themeum-startup-idea' ), 'administrator', 'themeum_startup_idea_withdraw', 'themeum_startup_idea_withdraw' );
		add_submenu_page( 'edit.php?post_type=investment', __( 'Paid project', 'themeum-startup-idea' ), __( 'Paid project', 'themeum-startup-idea' ), 'administrator', 'themeum_startup_idea_paid', 'themeum_startup_idea_paid' );
	}
add_action( 'admin_menu', 'themeum_admin_menu' );


//Add admin assets
function themeum_lms_load_admin_assets_js() {
	wp_enqueue_script( 'themeum-startup-script', plugins_url('themeum-startup-idea') . '/admin/assets/js/script.js', false );
}
add_action( 'admin_enqueue_scripts', 'themeum_lms_load_admin_assets_js' );




/*--------------------------------------------------------------
 * 					Personal Project Conform
 *-------------------------------------------------------------*/	
if(isset($_POST['project_confirm'])){
	$post_id = '';
    $post_id = $_POST['confirm-post-id'];
    update_post_meta($post_id, 'thm_withdraw_request', 'done' );
}