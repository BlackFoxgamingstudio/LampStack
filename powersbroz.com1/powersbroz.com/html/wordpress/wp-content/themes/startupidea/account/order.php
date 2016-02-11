<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active"><a href="?id=order"><?php _e('Recent Order','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">

    <?php
    $i=1;
    $count = 10;
    $id_list = themeum_my_project_id_list( get_current_user_id() );
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    
    if($paged != 1){ $i = ($count*($paged-1))+1; }

    $args = array(
                'post_type' => array( 'investment' ),
                'post_status' => 'publish',
                'meta_query' => array(
                                    array(
                                        'key'     => 'themeum_investment_project_id',
                                        'value'   => $id_list,
                                        'compare' => 'IN',
                                        ),
                                    ),
                'posts_per_page' => $count,
                'paged'=>$paged,
                'post_status' => 'publish'
            );
    // the query
    $the_query = new WP_Query( $args ); 

        if ( $the_query->have_posts() ) :

        echo '<table class="table table-hover">
                    <thead> 
                        <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php
                    $user_info = get_userdata( get_post_meta( get_the_ID(),'themeum_investor_user_id', true ) );
                ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo get_the_title( get_the_ID() ); ?></td>
                    <?php 
                        if(get_option('user_profile_page_id') == '' ){ 
                           if( isset($user_info->data->user_login) ){ echo '<td>'.$user_info->data->user_login.'</td>';}
                           else{ echo '<td></td>'; }
                        }else{
                            if( isset($user_info->data->user_login) ){ echo '<td><a target="_blank" href="'.get_permalink(get_option("user_profile_page_id")).'?user_id='.get_the_author_meta( "ID" ).'">'.$user_info->data->user_login.'</a></td>'; }
                            else{ echo '<td></td>'; }
                        }
                    ?>
                    <td><?php echo get_post_meta( get_the_ID(),'themeum_investment_date', true ); ?></td>
                    <td><?php echo get_post_meta( get_the_ID(),'themeum_investment_amount', true ); ?></td>
                </tr>

            <?php $i++; endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <h5><?php _e( 'Sorry, no posts matched your criteria.','themeum' ); ?></h5>
        <?php endif; ?>

        <?php echo '</tbody></table>'; ?>

        <?php
            $page_num = 0;
            $args = array( 
                        'post_type' => array( 'investment' ),
                        'post_status' => 'publish',
                        'meta_query' => array(
                                            array(
                                                'key'     => 'themeum_investment_project_id',
                                                'value'   => $id_list,
                                                'compare' => 'IN',
                                                ),
                                            ),
                'posts_per_page' => -1,
                'post_status' => 'publish'
                );
            $totalposts = get_posts($args);

            if(count($totalposts)!=0 && $count != 0 ){
                $page_num = ceil( count($totalposts)/$count );
            }else{
                $page_num = 1;
            }
            echo '<div class="dashboard-pagination text-right"';
            themeum_pagination( $page_num );
            echo '</div>';
        ?>

    </div>
</div> 