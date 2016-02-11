<?php
/**
 * Template Name: Homepage
 * Description: The template for displaying Homepage. 
 *
 * WARNING: This file is part of the Eventica parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category Page
 * @package  Templates
 * @author   TokoPress
 * @link     http://www.tokopress.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>

	<?php if( ! of_get_option( 'tokopress_home_slider_disable' ) ) : ?>
		<?php get_template_part( 'block-home/block', 'slider-event' ); ?>
	<?php else : ?>
		<?php if( ! of_get_option( 'tokopress_page_title_disable' ) ) : ?>
			<?php get_template_part( 'block-page-title' ); ?>
		<?php endif; ?>
	<?php endif; ?>

<div id="main-content" class="home-plus-events">

	<?php if( !of_get_option( 'tokopress_home_search_disable' ) ) : ?>
		<?php if( class_exists( 'Tribe__Events__Main' ) ) : ?>
			<?php get_template_part( 'block-home/block', 'search-event' ); ?>
		<?php else : ?>
			<?php get_template_part( 'block-home/block', 'search-form' ); ?>
		<?php endif; ?>
	<?php endif; ?>
			
	<?php if( !of_get_option( 'tokopress_home_upcoming_event_disable' ) ) : ?>
		<?php get_template_part( 'block-home/block', 'upcoming-events' ); ?>
	<?php endif; ?>

	<div class="home-group-box">
		<div class="container">
			<div class="row">

				<?php if( !of_get_option( 'tokopress_home_recent_post_disable' ) ) : ?>
					<div class="col-md-8 col-md-push-4">
				<?php else : ?>
					<div class="col-md-12">
				<?php endif; ?>

					<?php get_template_part( 'block-home/block', 'featured-event' ); ?>

					<?php if( defined( 'MC4WP_VERSION' ) || class_exists( "MC4WP_Lite" ) ) : ?>
						<?php if( !of_get_option( 'tokopress_home_subscribe_disable' ) ) : ?>
							<?php get_template_part( 'block-home/block', 'subscribe-form' ); ?>
						<?php endif; ?>
					<?php endif; ?>

				</div>

				<?php if( !of_get_option( 'tokopress_home_recent_post_disable' ) ) : ?>
					<div class="col-md-4 col-md-pull-8">
						<?php get_template_part( 'block-home/block', 'recent-post' ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

	<?php if( !of_get_option( 'tokopresss_home_testimonials_disable' ) ) : ?>
		<?php if( function_exists( 'register_tokopress_testimonial' ) ) : ?>
			<?php get_template_part( 'block-home/block', 'testimonial' ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if( !of_get_option( 'tokopresss_disable_brands_sponsors' ) ) : ?>
		<?php get_template_part( 'block-home/block', 'brand-sponsors' ); ?>
	<?php endif; ?>

</div>

<?php get_footer(); ?>