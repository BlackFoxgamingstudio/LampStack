<div class="col-md-12">
    <ul class="text-left submenu">
        <li><a href="?id=general"><?php _e('General Information','themeum'); ?></a></li>
        <li class="active" ><a href="?id=profile"><?php _e('Personal Profile','themeum'); ?></a></li>
<li><a href="?id=paypal"><?php _e('Paypal Setting','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">
        <?php 
            $data = get_user_meta(get_current_user_id());
        ?>
        <section class="profile-page dashboard-form">
            <div class="col-md-12">
                <h5><?php _e('This information will show on your profile and it is open for public.','themeum'); ?></h5>
                <form name="profile-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="wow fadeIn form-horizontal" id="profile-form">

                    <div class="form-group">
                        <label for="profile-image"><?php _e('Profile Image','themeum') ?></label>
                        <input type="text" name="profile-image" class="profile_image_url" value="<?php if( get_user_meta(get_current_user_id(),'profile_image_id',true) != '' ){ echo esc_url(wp_get_attachment_image_src(get_user_meta(get_current_user_id(),'profile_image_id',true),'full')[0]); } ?>">
                        <input type="hidden" name="profile-image-id" class="profile_image_id" value="<?php if( get_user_meta(get_current_user_id(),'profile_image_id',true) != '' ){ echo esc_attr(get_user_meta(get_current_user_id(),'profile_image_id',true)); } ?>">
                        <input type="button" id="cc-image-upload-file-button" class="profile-upload image-upload btn btn-primary" value="Upload Image" data-url="<?php echo esc_url(get_site_url()); ?>" />                  
                    </div>

                    <div class="form-group">
                        <label for="profile_about"><?php _e('About Us','themeum'); ?></label>
                        <textarea rows="10" class="form-control" name="profile_about"><?php if(isset($data['profile_about'][0])){ echo esc_textarea($data['profile_about'][0]); } ?></textarea> 
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_portfolio"><?php _e('Profile Information','themeum') ?></label>
                        <textarea rows="10" class="form-control" name="profile_portfolio"><?php if(isset($data['profile_portfolio'][0])){ echo esc_textarea($data['profile_portfolio'][0]); } ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_mobile1"><?php _e('Mobile Number','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_mobile1" value="<?php if(isset($data['profile_mobile1'][0])){ echo esc_attr($data['profile_mobile1'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_fax"><?php _e('Fax','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_fax" value="<?php if(isset($data['profile_fax'][0])){ echo esc_attr($data['profile_fax'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_email1"><?php _e('Email','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_email1" value="<?php if(isset($data['profile_email1'][0])){ echo esc_attr($data['profile_email1'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_website"><?php _e('Website','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_website" value="<?php if(isset($data['profile_website'][0])){ echo esc_url($data['profile_website'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_address"><?php _e('Address','themeum') ?></label>
                        <textarea class="form-control" name="profile_address"><?php if(isset($data['profile_address'][0])){ echo esc_textarea($data['profile_address'][0]); } ?></textarea>
                    </div>
                    
                    <h5><?php _e('Social Profile:','themeum'); ?></h5>
                    <div class="form-group">
                        <label for="profile_facebook"><?php _e('Facebook','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_facebook" value="<?php if(isset($data['profile_facebook'][0])){ echo esc_url($data['profile_facebook'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_twitter"><?php _e('Twitter','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_twitter" value="<?php if(isset($data['profile_twitter'][0])){ echo esc_url($data['profile_twitter'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_plus"><?php _e('Google Plus','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_plus" value="<?php if(isset($data['profile_plus'][0])){ echo esc_url($data['profile_plus'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_linkedin"><?php _e('Linkedin','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_linkedin" value="<?php if(isset($data['profile_linkedin'][0])){ echo esc_url($data['profile_linkedin'][0]); } ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_pinterest"><?php _e('Pinterest','themeum') ?></label>
                        <input type="text" class="form-control" name="profile_pinterest" value="<?php if(isset($data['profile_pinterest'][0])){ echo esc_url($data['profile_pinterest'][0]); } ?>">
                    </div>
                    
                    <div class="form-group pull-right">
                        <input type="hidden" name="user-id" value="<?php echo get_current_user_id(); ?>">
                        <input type="hidden" name="action" value="profile_form">
                        <button class="btn btn-success"  type="submit"><?php _e('Update','themeum'); ?></button>
                    </div>

                </form>
            </div> 
        </section>

    </div>
</div>


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