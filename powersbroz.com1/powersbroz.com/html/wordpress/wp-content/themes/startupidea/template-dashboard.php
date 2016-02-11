<?php
/*
 * Template Name: Page My Account
 */
get_header();
    
    global $themeum_options; 
    $get_id = '';
    get_template_part('lib/sub-header'); 
    if( isset($_GET['id']) ){ $get_id = $_GET['id']; }

    if ( is_user_logged_in() ) {
?>

<section id="dashboard">
    <div class="container">
        <div class="row">
            
            <div class="dashboard-main col-md-12 nopadding">
                <div class="dashboard-sidebar col-md-3 nopadding">
                    <ul>
                        <li <?php if( ($get_id=="") || ($get_id=="dashboard") || ($get_id=="profile") || ($get_id=="paypal" )  ){ echo 'class="active"'; } ?>><a href="?id=dashboard" ><i class="fa fa-desktop"></i> <?php _e('Dashboard','themeum'); ?></a></li>
                        <li <?php if( $get_id=="contact" ){ echo 'class="active"'; } ?>><a href="?id=contact" ><i class="fa fa-phone-square"></i> <?php _e('Contact Information','themeum'); ?></a></li>
                        <li <?php if( ($get_id=="project")||($get_id=="ratting") || ($get_id=="update") ){ echo 'class="active"'; } ?>><a href="?id=project" ><i class="fa fa-archive"></i> <?php _e('My Project','themeum'); ?></a></li>
                        <li <?php if( $get_id=="invest" ){ echo 'class="active"'; } ?>><a href="?id=invest" ><i class="fa fa-university"></i> <?php _e('My Investment','themeum'); ?></a></li>
                        <li <?php if( $get_id=="order" ){ echo 'class="active"'; } ?>><a href="?id=order" ><i class="fa fa-rocket"></i> <?php _e('My Order List','themeum'); ?></a></li>
                        <li <?php if( $get_id=="search" ){ echo 'class="active"'; } ?>><a href="?id=search" ><i class="fa fa-search"></i> <?php _e('Search Order','themeum'); ?></a></li>
                        <li <?php if( $get_id=="password" ){ echo 'class="active"'; } ?>><a href="?id=password" ><i class="fa fa-crosshairs"></i> <?php _e('Password Change','themeum'); ?></a></li>
                    </ul>
                </div>
                <div class="dashboard-content col-md-9 nopadding">
                    <div class="dashboard-menu">
                        <div class="col-md-12">
                            <ul class="text-right">
                                <?php $user_info = get_userdata(get_current_user_id()); ?>
                                <li><?php _e('Hi','themeum'); ?>, <?php echo $user_info->user_login; ?> !</li>
                                <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="fa fa-power-off"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Get the Current Template Portion -->
                    <?php 
                    $var = $get_id;
 
                    if(isset( $var ) ){
                        
                        switch ( $var ) {
                            case 'general':
                                get_template_part('account/general'); // General
                                break;

                            case 'profile':
                                get_template_part('account/profile'); // Profile
                                break;

                            case 'paypal':
                                get_template_part('account/paypal'); // Paypal
                                break;

                            case 'contact':
                                get_template_part('account/contact'); // Contact
                                break;
                            
                            case 'password':
                                get_template_part('account/password'); // Password
                                break;

                            case 'project':
                                get_template_part('account/project'); // Project
                                break;

                            case 'invest':
                                get_template_part('account/invest'); // Invest
                                break;

                            case 'order':
                                get_template_part('account/order'); // Order
                                break;

                            case 'search':
                                get_template_part('account/search'); // Search
                                break;

                            case 'ratting':
                                get_template_part('account/ratting'); // General
                                break;

                            case 'update':
                                get_template_part('account/update'); // Update
                                break;

                            case 'details':
                                get_template_part('account/details'); // Details
                                break;

                            case 'edit':
                                get_template_part('account/edit'); // Edit
                                break;

                                
                            default:
                                get_template_part('account/general'); // General
                                break;
                        }
                    }else{
                       get_template_part('account/general'); // General 
                    }
                    ?>
                    
                </div>
            </div>        
        </div>
    </div>
</section>


<div id="dashboard-msg" class="startup-modal modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">   
               <button type="button" id="dashboard-close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"><?php _e('Thanks','themeum') ?></h4>
                
             </div>
             <div class="modal-body text-center">
                 <p><?php _e('Thanks for update profile.','themeum') ?>.</p>
             </div>
         </div>
    </div> 
</div>

<div id="dashboard-msg-err" class="startup-modal modal fade">
    <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">   
               <button type="button" id="dashboard-close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"><?php _e('Sorry','themeum') ?></h4>
                
             </div>
             <div class="modal-body text-center">
                 <p><?php _e('Sorry for update profile error.','themeum') ?>.</p>
             </div>
         </div>
    </div> 
</div>
<?php } ?>

<?php get_footer();