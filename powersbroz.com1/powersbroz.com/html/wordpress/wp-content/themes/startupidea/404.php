<?php get_header('alternative'); 
/*
*Template Name: 404 Page Template
*/
global $themeum_options;
?>

<section id="error-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center wow fadeIn">
                <p><img src="<?php echo esc_url($themeum_options['errorpage']['url']); ?>" class="wow pulse" data-wow-delay=".25s"></p>
                <h3><?php  _e( '404 Error','themeum');?> </h3>
                <h4><?php  _e( 'Were sorry, but the page you were looking for doesnt exist.', 'themeum' ); ?></h4>

                <div class="clearfix"></div>
                <p class="home-icon">
                    <a href="<?php echo esc_url(site_url()); ?>" class="btn btn-default home-btn"><i class="fa fa-home"></i> <?php echo __('Back to Home','themeum'); ?></a>
                </p>
                <p class="copy-right"><?php if (isset($themeum_options['comingsoon-copyright'])) echo esc_html($themeum_options['comingsoon-copyright']); ?></p>
            </div>
        </div>
    </div>
</section>

<?php get_footer('alternative'); ?>
