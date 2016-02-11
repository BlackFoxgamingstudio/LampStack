<?php
/*
 * Template Name: Homepage Transparent Header
 */

get_header();

?>

<section id="main" class="clearfix">
    <div id="content" class="site-content" role="main">
        <?php /* The loop */ ?>
        <?php while ( have_posts() ): the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!--<h1 class="entry-title"><?php the_title(); ?></h1>-->

                <?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
                <div class="entry-thumbnail">
                    <?php the_post_thumbnail(); ?>
                </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                </div>

            </div>

            <?php // comment_template(); ?>

        <?php endwhile; ?>
    </div> <!--/#content-->
</section> <!--/#page-->
    
<?php get_footer(); ?>