<?php 
 function new_project_add(){

                $title = $attachment_id = $project_category = $user_id = $project_tag = $project_type = $start_date = $end_date = $location = $investment_amount = $video_url = $description = $sub_image_1 = $sub_image_2 = $sub_description_1 = $sub_description_2 = $about = $banner_image_id = '';
                $user_id = get_current_user_id();

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

                /*
                if(isset($_POST['project-image-id2'])){ $sub_image_1 = $_POST['project-image-id2']; }
                if(isset($_POST['sub_description_1'])){ $sub_description_1 = $_POST['sub_description_1']; }
                if(isset($_POST['project-image-id3'])){ $sub_image_2 = $_POST['project-image-id3']; }
                if(isset($_POST['sub_description_2'])){ $sub_description_2 = $_POST['sub_description_2']; }
                */

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



                if(isset($_POST['about'])){ $about = $_POST['about']; }
                if(isset($_POST['banner-image-id'])){ $banner_image_id = $_POST['banner-image-id']; }

                $cat = explode(' ',$project_category );
                $tag = explode(',',$project_tag );

                $post = array(
                  'post_type'           => 'project',
                  'post_title'          => $title,
                  'post_content'        => $description,
                  'post_status'         => 'draft',
                  'post_author'         => $user_id,
                );
                $post_id = wp_insert_post( $post );

                wp_set_object_terms( $post_id , $cat, 'project_category',true );
                wp_set_object_terms( $post_id , $tag, 'project_tag',true );
                


                if(is_int($post_id)){
                        add_post_meta($post_id, '_thumbnail_id', esc_attr($attachment_id));
                        add_post_meta($post_id, 'thm_type', esc_attr($project_type));
                        add_post_meta($post_id, 'thm_start_date', sanitize_text_field($start_date));
                        add_post_meta($post_id, 'thm_end_date', sanitize_text_field($end_date));
                        add_post_meta($post_id, 'thm_location', esc_attr($location));
                        add_post_meta($post_id, 'thm_funding_goal', esc_attr($investment_amount));
                        add_post_meta($post_id, 'thm_video_url', esc_url($video_url));

                        add_post_meta($post_id, 'themeum_reward', maybe_unserialize(serialize($output)) );
                        /*
                        add_post_meta($post_id, 'thm_sub_image_1', esc_attr($sub_image_1));
                        add_post_meta($post_id, 'thm_sub_description_1', esc_html($sub_description_1));
                        add_post_meta($post_id, 'thm_sub_image_2', esc_attr($sub_image_2));
                        add_post_meta($post_id, 'thm_sub_description_2', esc_html($sub_description_2));
                        */
                        add_post_meta($post_id, 'thm_about', esc_html($about));
                        add_post_meta($post_id, 'thm_subtitle_images', esc_attr($banner_image_id));
                        add_post_meta($post_id, 'thm_percentage', get_option('donate_page_percentage'));
                }

    }
add_action('wp_ajax_new_project_add', 'new_project_add');
add_action('wp_ajax_nopriv_new_project_add', 'new_project_add');  