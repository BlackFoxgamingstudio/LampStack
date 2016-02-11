<?php
/**
 * Template Name: Visual Composer
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

get_header(); ?>

	<div id="main-content">
		
		<div class="container">
					
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php the_content(); ?>

				<?php endwhile; ?>
				
			<?php endif; ?>

		</div>
		
	</div>

<?php get_footer(); ?>
