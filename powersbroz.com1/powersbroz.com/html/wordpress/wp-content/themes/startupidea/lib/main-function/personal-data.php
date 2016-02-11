<?php 
function profile_form(){

        update_user_meta( 1 ,'anik_biswas','test' );

        
        $user_id = $profile_name = $profile_about = $profile_portfolio = $profile_bio = $profile_mobile1 =  $profile_email1 = $profile_fax = $profile_address = $profile_facebook = $profile_twitter = $profile_plus = $profile_linkedin = $profile_pinterest = '';

        $user_id      = $_POST['user-id'];
        $profile_name     = $_POST['profile_name'];
        $profile_website  = $_POST['profile_website'];
        $profile_about    = $_POST['profile_about'];
        $profile_portfolio  = $_POST['profile_portfolio'];
        $profile_bio    = $_POST['profile_bio'];
        $profile_mobile1  = $_POST['profile_mobile1'];
        $profile_email1   = $_POST['profile_email1'];
        $profile_fax    = $_POST['profile_fax'];
        $profile_address  = $_POST['profile_address'];
        $profile_facebook   = $_POST['profile_facebook'];
        $profile_twitter  = $_POST['profile_twitter'];
        $profile_plus     = $_POST['profile_plus'];
        $profile_linkedin   = $_POST['profile_linkedin'];
        $profile_pinterest  = $_POST['profile_pinterest'];
        $profile_image_id   = $_POST['profile-image-id'];

        //add_user_meta
        update_user_meta($user_id,'profile_name',esc_attr($profile_name));
        update_user_meta($user_id,'profile_website',esc_url($profile_website));
        update_user_meta($user_id,'profile_about',$profile_about);
        update_user_meta($user_id,'profile_portfolio',esc_html($profile_portfolio));
        update_user_meta($user_id,'profile_bio',balanceTags($profile_bio));
        update_user_meta($user_id,'profile_mobile1',esc_attr($profile_mobile1));
        update_user_meta($user_id,'profile_mobile2',esc_attr($profile_mobile2));
        update_user_meta($user_id,'profile_email1',sanitize_email($profile_email1));
        update_user_meta($user_id,'profile_email2',sanitize_email($profile_email2));
        update_user_meta($user_id,'profile_fax',esc_attr($profile_fax));
        update_user_meta($user_id,'profile_address',balanceTags($profile_address));
        update_user_meta($user_id,'profile_facebook',esc_url($profile_facebook));
        update_user_meta($user_id,'profile_twitter',esc_url($profile_twitter));
        update_user_meta($user_id,'profile_plus',esc_url($profile_plus));
        update_user_meta($user_id,'profile_linkedin',esc_url($profile_linkedin));
        update_user_meta($user_id,'profile_pinterest',esc_url($profile_pinterest));
        update_user_meta($user_id,'profile_image_id',esc_attr($profile_image_id));
        
    }
add_action('wp_ajax_profile_form', 'profile_form');
add_action('wp_ajax_nopriv_profile_form', 'profile_form'); 



/*--------------------------------------------------------------
 *          Paypal User Data
 *-------------------------------------------------------------*/ 
function paypal_user_form(){
        $user_id = $paypal_email = '';

        $user_id          = $_POST['user-id'];
        $paypal_email     = sanitize_email( $_POST['paypal_email'] );
        
        //add_user_meta
        update_user_meta($user_id,'paypal_email',esc_attr($paypal_email));
    }
add_action('wp_ajax_paypal_user_form', 'paypal_user_form');
add_action('wp_ajax_nopriv_paypal_user_form', 'paypal_user_form'); 


/*--------------------------------------------------------------
 *          Personal Profile Data
 *-------------------------------------------------------------*/ 
function withdraw_request(){
    $post_id = '';
    $post_id = esc_attr($_POST['withdraw-post-id']);
    update_post_meta($post_id, 'thm_withdraw_request', 'yes' );
    }
add_action('wp_ajax_withdraw_request', 'withdraw_request');
add_action('wp_ajax_nopriv_withdraw_request', 'withdraw_request'); 


/*--------------------------------------------------------------
 *    Project Submit Update Action (Update New Project)
 *-------------------------------------------------------------*/ 

function update_form(){

        $post_id = $_POST['update-post-id'];
        $i=1;
        while(isset($_POST['update_title_'.$i])) {
                   if(isset($_POST['update_id_'.$i])){
                        if( $_POST['update_title_'.$i] == '' && $_POST['update_content_'.$i] == ''){
                            global $wpdb;
                            $wpdb->delete( $wpdb->prefix.'postmeta', array( 'meta_id' => $_POST['update_id_'.$i] ), array( '%d' ) );
                        }else{
                            $all_value =  $_POST['update_title_'.$i].'*###*'.$_POST['update_content_'.$i];
                            global $wpdb;
                            $wpdb->update( $wpdb->prefix.'postmeta', 
                                                array( 
                                                    'meta_key' => 'project_update',
                                                    'meta_value' => esc_attr($all_value)
                                                ), 
                                                array( 'meta_id' => esc_attr( $_POST['update_id_'.$i] )), 
                                                array( 
                                                    '%s',
                                                    '%s'
                                                    ),
                                                array('%d')
                                            );
                        }
                   }else{
                        if( $_POST['update_title_'.$i] != '' && $_POST['update_content_'.$i] != ''){
                            $all_value =  $_POST['update_title_'.$i].'*###*'.$_POST['update_content_'.$i];
                            add_post_meta($post_id, 'project_update', $all_value);
                        }
                   }
                   $i++;
            }
          
    }
add_action('wp_ajax_update_form', 'update_form');
add_action('wp_ajax_nopriv_update_form', 'update_form'); 


/*--------------------------------------------------------------
 *              Ratting
 *-------------------------------------------------------------*/
function ratting_update(){
  $post_id = $_POST['ratting-post-id']; //project_ratting
  $new_value = $_POST['project_ratting'].'*###*'.get_current_user_id();
  $output_arr = '';
  $find = 0;
  $output = get_post_meta(esc_attr($post_id),'project_ratting');
  
  // If already rate then update check
  if(is_array($output)){
      foreach ($output as $value) {
          $var = explode('*###*',$value);
          if($var[1]==1){
              update_post_meta(esc_attr($post_id), 'project_ratting', $new_value,$var[0].'*###*'.$var[1]);
              $find = 1;
          }
      }
  }
  // if not found then add
  if($find==0){
    add_post_meta(esc_attr($post_id), 'project_ratting', esc_html($new_value));
  }

}
add_action('wp_ajax_ratting_update', 'ratting_update');
add_action('wp_ajax_nopriv_ratting_update', 'ratting_update'); 