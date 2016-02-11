<?php 
function update_main_project() {
      $post_id = $title = $attachment_id = $project_category = $user_id = $project_tag = $project_type = $start_date = $end_date = $location = $investment_amount = $video_url = $description = $sub_image_1 = $sub_image_2 = $sub_description_1 = $sub_description_2 = $about = $banner_image_id = '';
        $user_id = get_current_user_id();
        
        if(isset($_POST['post-id'])){ $post_id = $_POST['post-id']; }
        if(isset($_POST['project-category'])){ $project_category = $_POST['project-category']; }
        if(isset($_POST['project-tag'])){ $project_tag = $_POST['project-tag']; }
        if(isset($_POST['project-title'])){ $title = $_POST['project-title']; }
        if(isset($_POST['project-image-id'])){ $attachment_id = $_POST['project-image-id']; }
        if(isset($_POST['project-type'])){ $project_type = $_POST['project-type']; }
        if(isset($_POST['start-date'])){ $start_date = $_POST['start-date']; }
        if(isset($_POST['end-date'])){ $end_date = $_POST['end-date']; }
        if(isset($_POST['location'])){ $location = $_POST['location']; }
        if(isset($_POST['investment-amount'])){ $investment_amount = $_POST['investment-amount']; }
        if(isset($_POST['video-url'])){ $video_url = $_POST['video-url']; }
        if(isset($_POST['description'])){ $description = $_POST['description']; }
        if(isset($_POST['about'])){ $about = $_POST['about']; }
        if(isset($_POST['banner-image-id'])){ $banner_image_id = $_POST['banner-image-id']; }

        $output = array();
        for ($i=1; $i <=50 ; $i++) { 
            if( isset( $_POST['min'.$i] ) && isset( $_POST['max'.$i] ) && isset( $_POST['reward'.$i] ) ){
                $arr = '';
                $arr['themeum_min'] = $_POST['min'.$i];
                $arr['themeum_max'] = $_POST['max'.$i];
                $arr['themeum_reward_data'] = $_POST['reward'.$i];
                $output[] = $arr;
            }
        }

        $cat = explode(' ',$project_category );
        $tag = explode(',',$project_tag );

        // Update POST
        $post = array(
              'ID'           => $post_id,
              'post_title'   => $title,
              'post_content' => $description,
          );
        // Update the post into the database
        wp_update_post( $post );
        wp_set_object_terms( $post_id , $cat, 'project_category',false );
        wp_set_object_terms( $post_id , $tag, 'project_tag',false );
        update_post_meta($post_id, '_thumbnail_id', esc_attr($attachment_id));
        update_post_meta($post_id, 'thm_type', sanitize_text_field($project_type));
        update_post_meta($post_id, 'thm_start_date', sanitize_text_field($start_date));
        update_post_meta($post_id, 'thm_end_date', sanitize_text_field($end_date));
        update_post_meta($post_id, 'thm_location', esc_attr($location));
        update_post_meta($post_id, 'thm_funding_goal', sanitize_text_field($investment_amount));
        update_post_meta($post_id, 'thm_video_url', esc_url($video_url));
        add_post_meta($post_id, 'themeum_reward', maybe_unserialize(serialize($output)));
        update_post_meta($post_id, 'thm_about', esc_html($about));
        update_post_meta($post_id, 'thm_subtitle_images', esc_attr($banner_image_id));
    }
add_action('wp_ajax_update_main_project', 'update_main_project');
add_action('wp_ajax_nopriv_update_main_project', 'update_main_project');  
