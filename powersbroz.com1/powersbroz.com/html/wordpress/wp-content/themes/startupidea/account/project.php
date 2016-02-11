<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active"><a href="?id=project"><?php _e('My Project List','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">




        <?php
        global $themeum_options;
        $args = array(
            'post_type' => 'project',
            'author'    => get_current_user_id(),
            'posts_per_page'      => -1
            );
   
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <div class="account-item">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="account-item-title pull-left"><i class="fa fa-check"></i><?php echo get_the_title(); ?></div>
                                <div class="edd_download_file pull-right">
                                    <div class="edd_download_file">
                                     
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

                                        <a target="_blank" href="<?php echo get_the_permalink(); ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> &nbsp; <?php _e('View','themeum'); ?></a>
                                        <a href="?project_id=<?php echo get_the_ID(); ?>&id=edit" class="btn btn-xs btn-danger"><i class="fa fa-pencil"></i> &nbsp; <?php _e('Edit','themeum'); ?></a>
                                        <a href="?project_id=<?php echo get_the_ID(); ?>&id=update" class="btn btn-xs btn-success"><i class="fa fa-cloud-upload"></i> &nbsp; <?php _e('Update','themeum'); ?></a>
                                        <a href="?project_id=<?php echo get_the_ID(); ?>&id=details" class="btn btn-xs btn-warning"><i class="fa fa-wrench"></i> &nbsp; <?php _e('Details','themeum'); ?></a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php _e( '<h5>Sorry, no posts matched your criteria.</h5>','themeum' ); ?></p>
        <?php endif; ?>






    </div>
</div>