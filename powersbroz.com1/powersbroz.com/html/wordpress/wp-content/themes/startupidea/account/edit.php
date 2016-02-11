<?php
$project_id = '';
if( isset( $_GET['project_id'] ) ){ $project_id = $_GET['project_id']; }

?>
<div class="col-md-12">
    <ul class="text-left submenu">
        <li><a href="?id=project"><?php _e('My Project List','themeum'); ?></a></li>
        <li class="active" ><a href="?id=edit&project_id=<?php if(isset($_GET['project_id'])){ echo $_GET['project_id']; } ?>"><?php _e('Update','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">
    

        <?php
        $args = array(
            'post_type' => 'project',
            'p' => $project_id,
            'posts_per_page'=> 1
            );

        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <!-- start project form -->
                        <section id="project-form">          

                            <!-- input form -->
                            <div class="col-md-12 input-form">
                               
                            <form name="project-submit-form" action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" class="wow fadeIn" id="project-submit-form">
                                
                                <div class="form-group has-feedback">
                                    <label for="project-title"><?php _e('Project Title','themeum') ?></label>
                                    <input type="hidden" name="update-project" id="update-project" value="new">
                                    <input type="hidden" name="action" value="update_main_project">
                                    <input type="hidden" name="post-id" id="post-id" value="<?php echo get_the_ID(); ?>">
                                    <input type="hidden" id="redirect_url_edit" name="uri" value="<?php echo get_the_permalink(); ?>">
                                    <input type="text" name="project-title" class="form-control" placeholder="Project Title" id="project-title" value="<?php echo get_the_title(); ?>">
                                    <span class="glyphicon glyphicon-ok form-control-feedback title-color"></span>
                                </div>
            
                                <div class="form-group has-feedback form-upload-image">
                                    <label for="project-image"><?php _e('Project Image','themeum') ?></label>
                                    <?php $image_source1 = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'full');?>
                                    <input type="text" name="project-image" class="form-control upload_image_url" id="project-image" value="<?php if(has_post_thumbnail()){ echo esc_url($image_source1[0]); } ?>">
                                    <input type="hidden" name="project-image-id" class="form-control upload_image_id" id="project-image" value="<?php if(has_post_thumbnail()){ echo get_post_thumbnail_id(get_the_ID()); } ?>">
                                    <input type="button" id="cc-image-upload-file-button" class="custom-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                                </div>

                                <div class="form-group has-feedback form-upload-image">
                                    <label for="banner-image"><?php _e('Banner Image','themeum') ?></label>
                                    <?php $image_source2 = wp_get_attachment_image_src(get_post_meta(get_the_ID(),'thm_subtitle_images',true),'full');?>
                                    <input type="text" name="banner-image" class="form-control banner_image_url" id="banner-image" value="<?php if(get_post_meta(get_the_ID(),'thm_subtitle_images',true)){  echo esc_url($image_source2[0]); } ?>">
                                    <input type="hidden" name="banner-image-id" class="form-control banner_image_id" id="banner-image" value="<?php if(get_post_meta(get_the_ID(),'thm_subtitle_images',true)){ echo esc_attr(get_post_meta(get_the_ID(),'thm_subtitle_images',true)); } ?>">
                                    <input type="button" id="cc-image-upload-file-button" class="banner-upload image-upload" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />
                                </div>

                                <?php 
                                $cat_array = get_the_terms( get_the_ID(), 'project_category' );
                                $cat_string = '';
                                if(is_array($cat_array)){
                                    foreach ($cat_array as $value) {
                                        $cat_string[] = $value->slug;
                                    }
                                }
                                 ?>
                                <div class="form-group has-feedback">
                                    <label for="project-category"><?php _e('Project Category','themeum') ?></label>
                                    <select name="project-category" id="project-category">
                                        <?php 
                                        $all_cat = get_terms('project_category'); 
                                        if(is_array($all_cat)){
                                            foreach ($all_cat as $value) {
                                                    if(in_array($value->slug,$cat_string)){
                                                        echo '<option selected value="'.$value->slug.'">'.$value->name.'</option>';
                                                    }else{
                                                        echo '<option value="'.$value->slug.'">'.$value->name.'</option>';
                                                    }
                                                }
                                        }

                                        ?>
                                    </select>
                                </div>
                                
                                <?php 
                                $tag_array = get_the_terms( get_the_ID(), 'project_tag' );
                                $tag_string = '';
                                if(is_array($tag_array)){
                                    foreach ($tag_array as $value) {
                                        $tag_string[] = $value->slug;
                                    }
                                    $tag_string = implode(',',$tag_string);
                                }
                                 ?>
                                <div class="form-group has-feedback">
                                    <label for="project-tag"><?php _e('Project Tag','themeum') ?></label>
                                    <input type="text" name="project-tag" class="form-control" placeholder="<?php echo _e('ex: food, art','themeum'); ?>" id="project-tag" value="<?php echo esc_attr($tag_string); ?>">
                                    <span class="glyphicon glyphicon-ok form-control-feedback tag-color"></span>
                                </div>

                                <div class="form-group has-feedback">
                                    <label for="project-category"><?php _e('Project Type','themeum') ?></label>
                                    <select name="project-type">
                                        <?php 
                                            if(get_post_meta(get_the_ID(),'thm_type',true)){ 
                                                    if(get_post_meta(get_the_ID(),'thm_type',true)=="Profitable"){
                                                        echo '<option value="Profitable" selected>Profitable</option>';
                                                    }else{
                                                        echo '<option value="Non Profitable" selected>Non Profitable</option>';
                                                    }
                                                } 
                                            ?>
                                        <option value="Profitable"><?php _e('Profitable','themeum') ?></option>
                                        <option value="Non Profitable"><?php _e('Non Profitable','themeum') ?></option>
                                    </select>
                                </div>

                                <div class="form-group project-duration">
                                    <label for="duration"><?php _e('Project Duration','themeum') ?></label>
                                    <input type="text" name="start-date" class="form-control datepicker" value="<?php if(get_post_meta(get_the_ID(),'thm_start_date',true)){ echo esc_attr(get_post_meta(get_the_ID(),'thm_start_date',true)); } ?>"><i class="divider fa fa-minus"></i>
                                    <input type="text" name="end-date" class="form-control datepicker" value="<?php if(get_post_meta(get_the_ID(),'thm_end_date',true)){ echo esc_attr(get_post_meta(get_the_ID(),'thm_end_date',true)); } ?>"> 
                                </div>

                                <div class="form-group has-feedback">
                                    <label for="location"><?php _e('Location','themeum') ?></label>
                                    <input type="text" name="location" class="form-control" id="location" value="<?php if(get_post_meta(get_the_ID(),'thm_location',true)){ echo esc_attr(get_post_meta(get_the_ID(),'thm_location',true)); } ?>">
                                    <span class="glyphicon glyphicon-ok form-control-feedback location-color"></span>
                                </div>

                                <div class="form-group">
                                    <label for="duration"><?php _e('Funding Goal','themeum') ?></label>
                                    <div class="input-group currency-change">
                                        <input type="text" name="investment-amount" id="investment-amount" class="form-control" value="<?php if(get_post_meta(get_the_ID(),'thm_funding_goal',true)){ echo esc_attr(get_post_meta(get_the_ID(),'thm_funding_goal',true)); } ?>">
                                        <div class="input-group-addon fund-goal">
                                            <span><?php echo themeum_get_currency_code(); ?></span> 
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group has-feedback">
                                    <label for="project-video"><?php _e('Video URL','themeum') ?></label>
                                    <input type="text" name="video-url" class="form-control" placeholder="Youtube Or Vimeo URL" id="project-video" value="<?php if(get_post_meta(get_the_ID(),'thm_video_url',true)){ echo esc_url(get_post_meta(get_the_ID(),'thm_video_url',true)); } ?>">
                                    <span class="glyphicon glyphicon-ok form-control-feedback video-color"></span>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php _e('Project Description','themeum') ?></label>
                                    <div>
                                        <?php
                                        $editor_id = 'description';
                                        $content = get_the_content();
                                        wp_editor( $content, $editor_id );
                                        ?>   
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label><?php _e('Reward','themeum') ?></label>
                                    <div id="clone-form">
                                        <?php
                                            $get_reward = get_post_meta( get_the_ID(),'themeum_reward',true ); 
                                            if (is_array($get_reward)){
                                                foreach ( $get_reward as $value ){
                                                    ?>
                                                    <div class="auto-field">
                                                        <label><?php _e('Range:','themeum') ?></label>
                                                        <input type="text" name="min1" placeholder="<?php _e('Minimum','themeum'); ?>" class="form-control min" value="<?php echo $value['themeum_min']; ?>"><i class="spce fa fa-minus"></i>
                                                        <input type="text" name="max1" placeholder="<?php _e('Maximum','themeum'); ?>" class="form-control max" value="<?php echo $value['themeum_max']; ?>">
                                                        <label class="reward-level"><?php _e('Reward1','themeum') ?></label>
                                                        <textarea name="reward1" class="form-control"><?php echo $value['themeum_reward_data']; ?></textarea><hr/>
                                                    </div>
                                                    <?php
                                                }
                                            }else{ ?>
                                                    <div class="auto-field">
                                                        <label><?php _e('Range:','themeum') ?></label>
                                                        <input type="text" name="min1" placeholder="<?php _e('Minimum','themeum'); ?>" class="form-control min" ><i class="spce fa fa-minus"></i>
                                                        <input type="text" name="max1" placeholder="<?php _e('Maximum','themeum'); ?>" class="form-control max" >
                                                        <label class="reward-level"><?php _e('Reward1','themeum') ?></label>
                                                        <textarea name="reward1" class="form-control"></textarea><hr/>
                                                    </div>
                                        <?php  }
                                        ?>
                                    </div>
                                    <span id="add-more"><?php _e('Add More','themeum') ?></span>
                                </div>


                                <div class="form-group has-feedback">
                                    <label for="project-about"><?php _e('Message','themeum') ?></label>
                                    <textarea id="project-about" name="about"><?php if(get_post_meta(get_the_ID(),'thm_about',true)){ echo esc_textarea(get_post_meta(get_the_ID(),'thm_about',true)); } ?></textarea>
                                    <span class="glyphicon glyphicon-ok form-control-feedback about-color"></span>
                                </div>

                            
                                <button id="project-submit" class="btn btn-primary pull-right" type="submit"><?php _e('Update Project','themeum') ?></button>

                                </form>


                            </div> <!-- end input form -->
                        </section>
                        <!-- end project form -->
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php _e( '<h5>Sorry, no posts matched your criteria.</h5>','themeum' ); ?></p>
        <?php endif; ?>


    </div>
</div>
