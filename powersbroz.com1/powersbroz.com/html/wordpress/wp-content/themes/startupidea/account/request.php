<?php
// General Form Action 
function general_form() {

	$id 			= get_current_user_id();
	$email 			= ( $_POST['email'] ) ? $_POST['email'] : "";
	$firstname 		= ( $_POST['firstname'] ) ? $_POST['firstname'] : "";
	$lastname 		= ( $_POST['lastname'] ) ? $_POST['lastname'] : "";
	$website 		= ( $_POST['website'] ) ? $_POST['website'] : "";
	$description 	= ( $_POST['description'] ) ? $_POST['description'] : "";
	
	$userdata = array( 
					'ID' 				=> $id,
					'user_email' 		=> $email,
					'first_name' 		=> $firstname,
					'last_name' 		=> $lastname,
					'user_url' 			=> $website,
					'description' 		=> $description,
					);

	wp_update_user( $userdata );
}
add_action('wp_ajax_general_form', 'general_form');
add_action('wp_ajax_nopriv_general_form', 'general_form');


// Contact Form Action
function contact_form() {

	$id 			= get_current_user_id();
	$first_name 	= ( $_POST['first_name'] ) ? $_POST['first_name'] : "";
	$last_name 		= ( $_POST['last_name'] ) ? $_POST['last_name'] : "";
	$email 			= ( $_POST['email'] ) ? $_POST['email'] : "";
	$address1 		= ( $_POST['address1'] ) ? $_POST['address1'] : "";
	$address2 		= ( $_POST['address2'] ) ? $_POST['address2'] : "";
	$city 			= ( $_POST['city'] ) ? $_POST['city'] : "";
	$state 			= ( $_POST['state'] ) ? $_POST['state'] : "";
	$zip 			= ( $_POST['zip'] ) ? $_POST['zip'] : "";
	$country 		= ( $_POST['country'] ) ? $_POST['country'] : "";


	update_user_meta( $id, 'startup_first_name', $first_name );
	update_user_meta( $id, 'startup_last_name', $last_name );
	update_user_meta( $id, 'startup_email', $email );
	update_user_meta( $id, 'startup_address1', $address1 );
	update_user_meta( $id, 'startup_address2', $address2 );
	update_user_meta( $id, 'startup_city', $city );
	update_user_meta( $id, 'startup_state', $state );
	update_user_meta( $id, 'startup_zip', $zip );
	update_user_meta( $id, 'startup_country', $country );
}
add_action('wp_ajax_contact_form', 'contact_form');
add_action('wp_ajax_nopriv_contact_form', 'contact_form');


// Password Form Action
function password_form() {

//add_option('anik','biswas');
	$id 			= get_current_user_id();
	$password 		= $_POST['password'];
	$new_password 	= $_POST['new-password'];
	$retype_password 	= $_POST['retype-password'];

	if( isset($_POST['password']) && isset($_POST['new-password']) && isset($_POST['retype-password']) ){
		
		if( ( $new_password == $retype_password ) && ( $retype_password != "" ) ){
			$user = get_user_by( 'id', $id );
			if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID) ){
				wp_set_password( $new_password, $id );
			}
		}
	}

}
add_action('wp_ajax_password_form', 'password_form');
add_action('wp_ajax_nopriv_password_form', 'password_form');




