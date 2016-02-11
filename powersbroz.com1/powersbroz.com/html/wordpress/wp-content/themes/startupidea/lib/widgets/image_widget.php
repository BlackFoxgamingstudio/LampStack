<?php

add_action('widgets_init','register_themeum_image_widget');

function register_themeum_image_widget()
{
	register_widget('Themeum_Image_Widget');
}

class Themeum_Image_Widget extends WP_Widget{

	function Themeum_Image_Widget()
	{
		parent::__construct( 'Themeum_Image_Widget','themeum Image Ads',array('description' => 'This Image Ads Widgets'));
	}


	/*-------------------------------------------------------
	 *				Front-end display of widget
	 *-------------------------------------------------------*/

	function widget( $args, $instance ) {

		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		?>

		<div class="single-ads">
			<?php	if($instance['ads_img1'])
			echo '<a href="'.esc_attr($instance['ads_img_link1']).'" target="_blank"><img src="'. esc_url(get_site_url()) . $instance['ads_img1'].'" class="img-responsive" alt=""></a>';
			?>
		</div>

		<?php echo $after_widget;
	}


	/*-------------------------------------------------------
	 *				Sanitize data, save and retrive
	 *-------------------------------------------------------*/

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ads_img_link1'] 		= $new_instance['ads_img_link1'];
		$instance['ads_img1'] 	= $new_instance['ads_img1'];
		$instance['ads_img_link2'] 		= $new_instance['ads_img_link2'];		

		return $instance;
	}


	/*-------------------------------------------------------
	 *				Back-End display of widget
	 *-------------------------------------------------------*/
	
	function form( $instance )
	{

		$defaults = array(  'title' => '',
			'ads_img_link1' => '#',
			'ads_img1' => ''
			);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title : ', 'themeum' ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'ads_img_link1' )); ?>"><?php _e( 'Ads Link', 'themeum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('ads_img_link1'));?>" name="<?php echo esc_attr($this->get_field_name('ads_img_link1')); ?>" value="<?php echo esc_attr($instance['ads_img_link1']); ?>">
			

			<label for="<?php echo esc_attr($this->get_field_id( 'ads_img1' )); ?>"><?php _e( 'Ads Image URL', 'themeum' ); ?></label>

			<input type="hidden" id="<?php echo esc_attr($this->get_field_id('ads_img1'));?>" name="<?php echo esc_attr($this->get_field_name('ads_img1'));?>" class="<?php echo esc_attr($this->get_field_id('ads_img1'));?>" value="<?php echo esc_attr($instance['ads_img1']); ?>"/>
 			<button id="<?php echo esc_attr($this->get_field_id('ads_img1'));?>" class="custom-upload button" data-url="<?php echo get_site_url(); ?>"><?php echo __('Upload image','themeum'); ?></button>
 			<img class="<?php echo esc_attr($this->get_field_id('ads_img1'));?>" src="<?php echo esc_url(get_site_url()) . esc_attr($instance['ads_img1']); ?> "/>
		</p>
	
		<?php
	}
}