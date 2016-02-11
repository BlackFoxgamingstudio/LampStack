<?php get_header(); ?>


<section id="main">

    <div id="content" class="site-content" role="main">

        <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    get_template_part( 'post-format/content', get_post_format() );
                endwhile;
            else:
                get_template_part( 'post-format/content', 'none' );
            endif;
        ?>
        <div class="container">
       <?php themeum_pagination(); ?>
       </div> <!-- #content -->

    </div> <!-- #content -->
            
</section> 

<?php get_footer();