<?php
/*
 * Template Name: Page User Profile
 */
get_header(); ?>

<?php  
if(isset($_GET['edit']) && is_user_logged_in() ):
?>
    <div id="profile-msg" class="startup-modal modal fade">
        <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">   
                   <button type="button" id="personal-close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title"><?php _e('Thanks','themeum') ?></h4>
                    
                 </div>
                 <div class="modal-body text-center">
                     <p><?php _e('Thanks for update profile.','themeum') ?>.</p>
                 </div>
             </div>
        </div> 
    </div>


    <?php 
        $data = get_user_meta(get_current_user_id());
    ?>
    <section class="profile-page">
    <?php get_template_part('lib/sub-header')?>
        <div class="container">
            <div class="row">
                <h2><?php echo __('Personal Profile Edit','themeum'); ?></h2>
                <form name="personal-profile-form" action="<?php admin_url("admin-ajax.php"); ?>" method="post" class="wow fadeIn form-horizontal" id="personal-profile-form">
                    
                    
                    <label for="profile-image"><?php _e('Personal Image','themeum') ?></label>
                    <input type="text" name="profile-image" class="profile_image_url" value="<?php if( get_user_meta(get_current_user_id(),'profile_image_id',true) != '' ){ echo esc_url(wp_get_attachment_image_src(get_user_meta(get_current_user_id(),'profile_image_id',true),'full')[0]); } ?>">
                    <input type="hidden" name="profile-image-id" class="profile_image_id" value="<?php if( get_user_meta(get_current_user_id(),'profile_image_id',true) != '' ){ echo esc_attr(get_user_meta(get_current_user_id(),'profile_image_id',true)); } ?>">
                    <input type="button" id="cc-image-upload-file-button" class="profile-upload image-upload btn btn-primary" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />                  
                    <label for="profile_about"><?php _e('About Us','themeum') ?></label>
                    <textarea name="profile_about"><?php if(isset($data['profile_about'][0])){ echo esc_textarea($data['profile_about'][0]); } ?></textarea>
                    
                    <label for="profile_portfolio"><?php _e('Profile Information','themeum') ?></label>
                    <textarea name="profile_portfolio"><?php if(isset($data['profile_portfolio'][0])){ echo esc_textarea($data['profile_portfolio'][0]); } ?></textarea>
                    
                    <label for="profile_mobile1"><?php _e('Mobile Number','themeum') ?></label>
                    <input type="text" name="profile_mobile1" value="<?php if(isset($data['profile_mobile1'][0])){ echo esc_attr($data['profile_mobile1'][0]); } ?>">
                    <label for="profile_fax"><?php _e('Fax','themeum') ?></label>
                    <input type="text" name="profile_fax" value="<?php if(isset($data['profile_fax'][0])){ echo esc_attr($data['profile_fax'][0]); } ?>">
                    <label for="profile_email1"><?php _e('Email','themeum') ?></label>
                    <input type="text" name="profile_email1" value="<?php if(isset($data['profile_email1'][0])){ echo esc_attr($data['profile_email1'][0]); } ?>">
                    <label for="profile_website"><?php _e('Website','themeum') ?></label>
                    <input type="text" name="profile_website" value="<?php if(isset($data['profile_website'][0])){ echo esc_url($data['profile_website'][0]); } ?>">
                    <label for="profile_address"><?php _e('Address','themeum') ?></label>
                    <textarea name="profile_address"><?php if(isset($data['profile_address'][0])){ echo esc_textarea($data['profile_address'][0]); } ?></textarea>
                    <label for="profile_facebook"><?php _e('Facebook','themeum') ?></label>
                    <input type="text" name="profile_facebook" value="<?php if(isset($data['profile_facebook'][0])){ echo esc_url($data['profile_facebook'][0]); } ?>">
                    <label for="profile_twitter"><?php _e('Twitter','themeum') ?></label>
                    <input type="text" name="profile_twitter" value="<?php if(isset($data['profile_twitter'][0])){ echo esc_url($data['profile_twitter'][0]); } ?>">
                    <label for="profile_plus"><?php _e('Google Plus','themeum') ?></label>
                    <input type="text" name="profile_plus" value="<?php if(isset($data['profile_plus'][0])){ echo esc_url($data['profile_plus'][0]); } ?>">
                    <label for="profile_linkedin"><?php _e('Linkedin','themeum') ?></label>
                    <input type="text" name="profile_linkedin" value="<?php if(isset($data['profile_linkedin'][0])){ echo esc_url($data['profile_linkedin'][0]); } ?>">
                    <label for="profile_pinterest"><?php _e('Pinterest','themeum') ?></label>
                    <input type="text" name="profile_pinterest" value="<?php if(isset($data['profile_pinterest'][0])){ echo esc_url($data['profile_pinterest'][0]); } ?>"><br>
                    <button id="personal-submit-form" class="btn btn-success"  type="submit"><?php _e('Update','themeum'); ?></button>

                    <input type="hidden" name="user-id" value="<?php echo get_current_user_id(); ?>">
                    <input type="hidden" name="personal-profile" value="personal">
                    <input type="hidden" name="action" value="personal_profile">
                    <input type="hidden" id="redirect_url_personal" name="uri" value="<?php echo get_the_permalink(); ?>">
     
                </form>
            </div>
        </div>
    </section>

<?php
else:
?>

        <section class="profile-page-inner">
            <?php get_template_part('lib/sub-header')?>
            <div class="container">
                <?php 
                 $data = '';
                 $user_ids = '';
                if(isset($_GET['user_id'])){
                    $data = get_user_meta($_GET['user_id']); 
                    $user_ids = $_GET['user_id'];
                }else{
                    $data = get_user_meta(get_current_user_id()); 
                    $user_ids = get_current_user_id();
                    echo '<h2><a class="btn btn-info btn-sm btn-useredit" href="?edit=true">Edit</a></h2>';
                }
                ?>
                
                
                <div class="overview-inner clearfix">
                    <h3><?php _e('Overview','themeum');?></h3> 
                    <?php if( get_user_meta($user_ids,'profile_image_id',true) != '' ): ?>
                        <img width="250" class="pull-left img-responsive" src="<?php echo esc_url(wp_get_attachment_image_src(get_user_meta($user_ids,'profile_image_id',true))[0]); ?>">
                    <?php endif; ?>
                    <?php if(isset($data['profile_about'][0])){ echo esc_html($data['profile_about'][0]); } ?>  
                </div>
                
                <div class="row profile-info">
                    <div class="col-sm-7">
                        <h3><?php _e('What We Do','themeum');?></h3> 
                        <?php if(isset($data['profile_portfolio'][0])){ echo esc_html($data['profile_portfolio'][0]); } ?>                
                    </div>
                    <div class="col-sm-5">
                        <h3><?php _e('Company Information','themeum');?></h3> 
                        <div class="compnay-address">
                            <?php if(isset($data['profile_mobile1'][0])){ ?>
                             <p><strong><i class="fa fa-phone"></i></strong> <?php echo esc_html($data['profile_mobile1'][0]); ?></p>
                             <?php } ?>
                             <?php if(isset($data['profile_fax'][0])){ ?>
                             <p><strong><i class="fa fa-fax"></i></strong> <?php echo esc_html($data['profile_fax'][0]); ?></p>
                             <?php } ?>
                             <?php if(isset($data['profile_email1'][0])){ ?>
                             <p><strong><i class="fa fa-envelope-o"></i> </strong> <?php echo sanitize_email($data['profile_email1'][0]); ?></p>
                             <?php } ?>
                             <?php if(isset($data['profile_website'][0])){ ?>
                             <p><strong><i class="fa fa-globe"></i></strong> <?php echo esc_url($data['profile_website'][0]);?></p>
                             <?php } ?>
                             <?php if(isset($data['profile_address'][0])){ ?>
                             <p><strong><i class="fa fa-map-marker"></i></strong> <?php echo esc_html($data['profile_address'][0]); ?></p>
                             <?php } ?>
                         </div>

                    
                        <div class="social-button">
                            <ul class="list-unstyled list-inline">
                                <?php if(isset($data['profile_facebook'][0])){ ?>
                                <li><a class="facebook" href="<?php echo esc_url($data['profile_facebook'][0]);?>"><i class="fa fa-facebook"></i></a></li>
                                <?php } ?>
                                <?php if(isset($data['profile_twitter'][0])){ ?>
                                <li><a class="twitter" href="<?php echo esc_url($data['profile_twitter'][0]); ?>"><i class="fa fa-twitter"></i></a></li>
                                <?php } ?>
                                <?php if(isset($data['profile_plus'][0])){ ?>
                                <li><a class="g-puls" href="<?php echo esc_url($data['profile_plus'][0]); ?>"><i class="fa fa-google-plus"></i></a></li>
                                <?php } ?>
                                <?php if(isset($data['profile_linkedin'][0])){ ?>
                                <li><a class="linkedin" href="<?php echo esc_url($data['profile_linkedin'][0]);?>"><i class="fa fa-linkedin"></i></a></li>
                                <?php } ?>
                                <?php if(isset($data['profile_pinterest'][0])){ ?>
                                <li><a class="pinterest" href="<?php echo esc_url($data['profile_pinterest'][0]); ?>"><i class="fa fa-pinterest"></i></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                 </div>
            </div>
        </section>

<?php
endif;
?>

<?php
get_footer();
