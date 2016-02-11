<?php get_header(); ?>
<?php get_template_part('lib/sub-header')?>

<section id="main" class="container">
    <div class="row">
        <div id="content" class="site-content col-md-8" role="main">

            <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'post-format/content', get_post_format() ); ?>
                <?php endwhile; ?>

                <?php echo themeum_pagination(); ?>

            <?php else: ?>
                <?php get_template_part( 'post-format/content', 'none' ); ?>
            <?php endif; ?>

        </div> <!-- #content -->

        <div id="sidebar" class="col-md-4" role="complementary">
            <div class="sidebar-inner">
                <aside class="widget-area">
                    <?php dynamic_sidebar('sidebar');?>
                </aside>
            </div>
        </div>

    </div>
</section>

<?php get_footer();