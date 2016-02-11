<?php

get_header(); ?>

<section id="main">
    
   <?php get_template_part('lib/sub-header')?>

    <div class="container-fluid">
        <div id="content" class="site-content row" role="main">

            <?php $page_class = array('col-md-12'); ?>
            <?php while ( have_posts() ): the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class($page_class); ?>>

                <?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
                <div class="row">
                    <div class="entry-thumbnail col-md-12">
                        <?php the_post_thumbnail(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="entry-content row">
                <?php $content = get_the_content(); ?>
                <?php
                if(!has_shortcode($content, 'vc_row')){
                    ?>
                    <div class="col-md-12">
                        <?php the_content(); ?>
                    </div>
                    <?php
                }else{
                    the_content();
                }
                wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'themeum' ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                ) ); ?>
            </div>
        </div>

            <?php endwhile; ?>
        </div> <!--/#content-->
    </div>
</section> <!--/#main-->
<?php get_footer();
