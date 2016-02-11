<?php
ob_start();

   if (isset($_POST['submit']) && isset($_POST['log_username']) ) {

       	global $errors_login;
        $errors_login ='';
        $username   =   sanitize_user($_POST['log_username']);
        $password   =   sanitize_text_field($_POST['log_password']);
        $remember   =   '';
        if(isset($_POST['remember'])){ $remember = sanitize_text_field($_POST['remember']); }

        $user = '';

 if (username_exists($username)){
        $creds = array();
		$creds['user_login'] = $username;
		$creds['user_password'] = $password;
		$creds['remember'] = '';
		
		if($remember == 'true'){
			$creds['remember'] = $remember;
		}
		else{
			$creds['remember'] = 'false';
		}
		
		$user = wp_signon( $creds, false );
	}


		if ( is_wp_error($user) ){
			$errors_login = '<div class="col-sm-4 col-sm-offset-4 text-center"><div class="alert alert-danger" role="alert"><strong>'.__("ERROR","themeum").'</strong>: '.__("Username or Password is not valid.","themeum").'</div></div>';
		 }elseif( $user == "" ){
		 	$errors_login = '<div class="col-sm-4 col-sm-offset-4 text-center"><div class="alert alert-danger" role="alert"><strong>'.__("ERROR","themeum").'</strong>: '.__("Username or Password is not valid.","themeum").'</div></div>';
		 }else{
		 	wp_safe_redirect(esc_url($_SERVER['REQUEST_URI'])); 
		 	exit;
		 }

    }



	function login_form(){
	    global $errors_login;
	    echo $errors_login;
	    echo '
	    	<div class="col-sm-4 col-sm-offset-4 text-center">
		    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
		    	<a href="'.esc_url(get_site_url()).'">
						<img class="center-image" src="'.get_template_directory_uri().'/images/logo.png" alt="" >
				</a>
		    	<p class="lead">'.__("Welcome Back!","themeum").'</p>
			    <div class="form-group">
				    <input autocomplete="off" type="text" name="log_username" class="required form-control"  placeholder="'.__("Username","themeum").'" />
			    </div>
			    <div class="form-group">
				    <input autocomplete="off" type="password" class="password required form-control" placeholder="'.__("Password","themeum").'" name="log_password" >
			    </div>
			    <div class="form-group">
			    	<input type="checkbox" name="remember" value="true"> '.__("Remember Me","themeum").'
			    	<input type="submit" class="btn btn-success btn-lg btn-block" name="submit" value="'.__("Log In","themeum").'"/>
			    </div>
		    ';
		if( $errors_login != '' ){
	    	echo '<p><a href="'.wp_lostpassword_url().'" title="'.__("Lost Password","themeum").'">'.__("Lost Password?","themeum").'</a></p>';	
	    }
	    

	    if(get_option('register_page_id')){ 
	    	echo "<p>".__('Don not have an account?','themeum')." <a href='".esc_url(get_permalink( get_option('register_page_id') ))."' title='".__('Sign Up','themeum')."'>".__('Sign Up','themeum')."</a></p>";	
	    }

	   echo '</form></div>';

	}


	// Register login shortcode.
	add_shortcode('custom_login', 'custom_login_shortcode');
	function custom_login_shortcode(){
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$paid_product = '<div class="list-group"><a href="#" class="list-group-item active">'.__("Purchase Product List","themeum").'</h4></a>';
				$args = array('post_type'         => 'lmsorder', 'meta_query'        => array(array('key'     => 'themeum_order_user_id','value'   => $current_user->ID,'compare' => '='  )));
			    $e_query = new WP_Query($args);
			    
			    while ( $e_query->have_posts() ) :  $e_query->the_post();
				    $paid_product .= '<a href="'.get_the_permalink(get_post_meta( get_the_ID() , "themeum_order_course_id" , true )).'" class="list-group-item"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> '.esc_html(get_the_title( get_post_meta( get_the_ID() , "themeum_order_course_id" , true ))).'</a>';
				endwhile;
				
				$paid_product .= '</div>';
		    	ob_start();
		    	
		    	echo '  <div class="margin15">
		    			<a class="pull-right btn btn-primary btn-lg" href="'.esc_url(wp_logout_url( home_url() )).'" >'.__("Logout","themeum").'</a>
		    			</div>
		    			<table class="table table-hover table-responsive">
						  	<tr>
						  		<td>'.__("Name: ","themeum").'</td>
						  		<td>'.esc_html($current_user->user_nicename).'</td>
						  	</tr>
						  	<tr>
						  		<td>'.__("Username: ","themeum").'</td>
						  		<td>'.esc_html($current_user->user_login).'</td>
						  	</tr>
						  	<tr>
						  		<td>'.__("Email: ","themeum").'</td>
						  		<td>'.sanitize_email($current_user->user_email).'</td>
						  	</tr>
						  	<tr>
						  		<td>'.__("Website: ","themeum").'</td>
						  		<td>'.esc_url($current_user->user_url).'</td>
						  	</tr>
						</table>
		    		';
		    	

		    	echo $paid_product;
	     }
	    else{
	    	login_form();
	    }
	    return ob_get_clean();
	}
