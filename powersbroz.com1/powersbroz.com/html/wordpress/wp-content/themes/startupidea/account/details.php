<div class="col-md-12">
    <ul class="text-left submenu">
        <li><a href="?id=project"><?php _e('My Project List','themeum'); ?></a></li>
        <li class="active" ><a href="?id=details&project_id=<?php if(isset($_GET['project_id'])){ echo $_GET['project_id']; } ?>"><?php _e('Details','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">
       



            <section class="details-page">
                        <div class="col-md-12">
                        <?php 
                        if(isset($_GET['project_id'])): 

                            $posts = new WP_Query(array(
                                                'post_type' => 'project',
                                                'p' => $_GET['project_id'],
                                                'posts_per_page' => 1
                                                ));
                            
                            while ( $posts->have_posts() ) : $posts->the_post();

                            $type       = rwmb_meta("thm_type");
                            $location   = rwmb_meta("thm_location");
                            $start      = rwmb_meta("thm_start_date"); 
                            $end        = rwmb_meta("thm_end_date"); 
                            $goal       = rwmb_meta("thm_funding_goal"); 
                        ?>
                            <div class="personal-title">
                                <h3><?php _e('Basic Information','themeum'); ?></h3>
                                <table class="table table-hover table-responsive">
                                    <tbody>
                                        <tr>
                                            <td><strong><?php _e('Type','themeum'); ?>: </strong></td>
                                            <td>Profitable</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Location','themeum'); ?>: </strong></td>
                                            <td><?php echo esc_attr($location); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Project Start Date','themeum'); ?>: </strong></td>
                                            <td><?php echo esc_attr($start); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Project End Date','themeum'); ?>: </strong></td>
                                            <td><?php echo esc_attr($end); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Funding Goal','themeum'); ?>: </strong></td>
                                            <td><?php echo themeum_get_currency_symbol(); ?><?php echo esc_attr($goal); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h3><?php _e('Other Information','themeum'); ?></h3>
                                <table class="table table-hover table-responsive">
                                    <tbody>
                                        <tr>
                                            <td><strong><?php _e('Funding(Amount)','themeum'); ?>: </strong></td>
                                            <td><?php echo themeum_get_project_info(get_the_ID(),'collected'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Funding(Persent)','themeum'); ?>: </strong></td>
                                            <td><?php echo themeum_get_project_info(get_the_ID(),'percent'); ?>%</td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Ratting','themeum'); ?>: </strong></td>
                                            <td><?php echo themeum_get_ratting_data(get_the_ID()); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php _e('Withdraw','themeum'); ?>: </strong></td>
                                            <td>
                                                <?php 
                                                    if(themeum_get_project_info(get_the_ID(),'percent') >= 100 ){
                                                        if( get_post_meta( get_the_ID(), 'thm_withdraw_request', true) == 'yes' ){
                                                            echo '<label class="btn btn-xs btn-warning"><i class="fa fa-external-link-square"></i> &nbsp; '.__('Withdraw request sent','themeum').'</label>';
                                                            }else if( get_post_meta( get_the_ID(), 'thm_withdraw_request', true) == 'done'){
                                                                echo '<label class="btn btn-xs btn-primary"><i class="fa fa-bullhorn"></i> &nbsp; '.__('Paid','themeum').'</label>';
                                                            }else{
                                                            ?>
                                                                <?php  echo themeum_message_generator('withdraw',$themeum_options['withdraw-msg-title'],$themeum_options['withdraw-msg-body']); ?>
                                                                
                                                                <form name="withdraw-submit-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="wow fadeIn form-horizontal" id="withdraw-submit-form">
                                                                    <input type="hidden" name="withdraw-post-id" value="<?php echo get_the_ID(); ?>">
                                                                    <input type="hidden" name="action" value="withdraw_request">
                                                                    <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                                                    <input type="hidden" id="redirect_url_withdraw" name="uri" value="<?php echo esc_url($actual_link); ?>">
                                                                    <input type="hidden" name="project_withdraw" value="1">
                                                                    <button class="btn btn-xs btn-warning" type="submit"><i class="fa fa-fighter-jet"></i> &nbsp;<?php echo __('Withdraw','themeum'); ?></button>
                                                                </form>
                                                            <?php
                                                            }
                                                        }else{
                                                            echo '<label class="btn btn-xs btn-info"><i class="fa fa-money"></i> &nbsp; '.themeum_get_project_info(get_the_ID(),'percent').'%</label>';
                                                        }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                        <?php 
                        endwhile;
                        wp_reset_query();
                        endif; 
                        ?>
                        </div>
                    
            </section>




    </div>
</div>
