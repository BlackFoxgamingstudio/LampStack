<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active" ><a href="?id=invest"><?php _e('My Donate/Invest List','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">

        <?php

        $id_list = themeum_user_project_id_list( get_current_user_id() );

        $args = array(
                    'post_type' => array( 'project' ),
                    'post__in' => $id_list,
                    'post_status' => 'publish',
                    'posts_per_page' => -1
                );

        // the query
        $the_query = new WP_Query( $args ); 
            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                
                
                <div class="account-item">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="account-item-title pull-left"><i class="fa fa-check"></i><?php echo get_the_title(); ?></div>
                            <div class="edd_download_file pull-right">
                                <a href="<?php echo get_the_permalink(); ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> &nbsp; <?php _e('View','themeum'); ?></a>
                                <label class="btn btn-xs btn-info"><i class="fa fa-money"></i> &nbsp; <?php echo themeum_get_currency_symbol(); ?><?php echo themeum_get_project_info(get_the_ID(),'budget'); ?></label>
                                <a href="?project_id=<?php echo get_the_ID(); ?>&id=ratting" class="btn btn-xs btn-success"><i class="fa fa-star"></i> &nbsp; <?php _e('Rate','themeum'); ?></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>


            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <h5><?php _e( 'Sorry, no posts matched your criteria.','themeum' ); ?></h5>
        <?php endif; ?>

    </div>
</div>