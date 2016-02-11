<?php
/*
 * Template Name: Page Donate
 */
get_header(); ?>

<section id="main" class="clearfix donate-project">
   <?php get_template_part('lib/sub-header')?>
    <div id="content" class="site-content" role="main">


        <?php /* The loop */ ?>
        <?php while ( have_posts() ): the_post(); ?>

            <div id="post-<?php the_ID(); ?>"  <?php post_class('donate-project-page clearfix'); ?>>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-7 donate-box wow fadeIn">
                            <?php the_content(); ?>

                            <?php 
                            if(isset($_GET['project_id'])){
                                if(function_exists('insert_post_buynow')):
                                    echo  insert_post_buynow( $_GET['project_id'] );  
                                endif;
                            }
                            ?>
                        </div>

                        <div id="payment-method" class="col-sm-offset-1 col-sm-4 wow fadeIn" data-wow-delay=".15s">
                            <div class="fund-info-box-1">
                                <h4><?php echo esc_textarea(get_option('donate_page_notice_title')); ?></h4>
                                <p><?php echo esc_textarea(get_option('donate_page_notice_content')); ?></p>
                            </div>
                            
                            <div class="fund-info-box-2">
                                <h4><?php echo esc_textarea(get_option('donate_page_notice_text')); ?></h4>
                                <p><?php echo nl2br(esc_textarea(get_option('donate_page_notice_text_content'))); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>

    </div> <!--/#content-->
</section> <!--/#main-->
<?php get_footer();
