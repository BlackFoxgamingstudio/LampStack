<div class="col-md-12">
    <ul class="text-left submenu">
        <li><a href="?id=general"><?php _e('General Information','themeum'); ?></a></li>
        <li><a href="?id=profile"><?php _e('Personal Profile','themeum'); ?></a></li>
        <li class="active" ><a href="?id=paypal"><?php _e('Paypal Setting','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">
        <?php 
            $data = get_user_meta(get_current_user_id());
        ?>
        <section class="profile-page dashboard-form">
            <div class="col-md-12">
                <h5><?php _e('Please set here your paypal Email address.','themeum'); ?></h5>
                <form name="paypal-user-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="wow fadeIn form-horizontal" id="paypal-user-form">
                    
                    <div class="form-group">
                        <label for="paypal_email"><?php _e('Paypal Email:','themeum') ?></label>
                        <input type="text" class="form-control" name="paypal_email" value="<?php if(isset($data['paypal_email'][0])){ echo sanitize_email($data['paypal_email'][0]); } ?>">
                    </div>
                    
                    <div class="form-group pull-right">
                        <input type="hidden" name="user-id" value="<?php echo get_current_user_id(); ?>">
                        <input type="hidden" name="action" value="paypal_user_form">
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
                 <p><?php _e('Thanks for update paypal email.','themeum') ?>.</p>
             </div>
         </div>
    </div> 
</div>