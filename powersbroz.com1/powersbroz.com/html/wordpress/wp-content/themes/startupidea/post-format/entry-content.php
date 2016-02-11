<?php global $themeum_options; ?>

<figure>
    <div class="entry-meta clearfix">
        <ul>
        <?php if (isset($themeum_options['blog-date']) && $themeum_options['blog-date'] ) { ?>
            <li class="date"> <i class="fa fa-clock-o"></i> <time class="entry-date" datetime="<?php the_time( 'c' ); ?>"><?php the_time('M j,  Y'); ?></time></li>  
        <?php }?>   

        <?php if (isset($themeum_options['blog-category']) && $themeum_options['blog-category'] ) { ?>
        <li class="category"> <i class="fa fa-folder-open-o"></i> <?php echo get_the_category_list(', '); ?></li>
        <?php }?>        

        <?php if (isset($themeum_options['blog-tag']) && $themeum_options['blog-tag'] ) { ?>
            <li class="tag"> <i class="fa fa-tags"></i> <?php the_tags('', ', ', '<br />'); ?> </li>
        <?php } ?>

        <?php if (isset($themeum_options['blog-comment']) && $themeum_options['blog-comment'] ){ ?> 
        <?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
            <li class="comments-link">
              <i class="fa fa-comments-o"></i>  <?php comments_popup_link( '<span class="leave-reply">' . __( 'No comment', 'themeum' ) . '</span>', __( 'One comment', 'themeum' ), __( '% comments', 'themeum' ) ); ?>
            </li>
        <?php endif; //.comment-link ?>
        <?php } ?>

        <?php if (isset($themeum_options['blog-edit-en']) && $themeum_options['blog-edit-en']) { ?>
            <li class="edit-link">
                 <i class="fa fa-edit"></i> <?php edit_post_link( __( 'Edit', 'themeum' ), '<span class="edit-link">', '</span>' ); ?>
            </li>
        <?php } ?>

        </ul>

        <div class="entry-headder">
            <div class="entry-title-wrap">
                <h2 class="entry-title blog-entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                    <?php if ( is_sticky() && is_home() && ! is_paged() ) { ?>
                    <sup class="featured-post"><i class="fa fa-star"></i></sup>
                    <?php } ?>
                </h2> <!-- //.entry-title --> 
            </div>
        </div>
         
        <?php if (isset($themeum_options['blog-author']) && $themeum_options['blog-author'] ) { ?>
            <p><?php  _e('By ', 'themeum'); ?> <strong><?php the_author_posts_link() ?></strong></p>
        <?php }?> 

    </div>
    <?php  if (isset($themeum_options['blog-social']) && $themeum_options['blog-social'] ){
        if(is_single()) {
            get_template_part( 'post-format/social-buttons' );
        }
    } ?>
</figure>






