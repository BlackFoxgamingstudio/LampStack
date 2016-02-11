<?php

function tokopress_widgets_init() {
	register_widget('TokoPress_Widget_Recent_Posts');
	if( class_exists( 'Tribe__Events__Main' ) ) {
		register_widget('TokoPress_Widget_Upcoming_Events');
		register_widget('TokoPress_Widget_Past_Events');
	}
}
add_action('widgets_init', 'tokopress_widgets_init');

class TokoPress_Widget_Recent_Posts extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_posts', 'description' => __( "Your site&#8217;s most recent posts with thumbnail.", 'tokopress') );
		parent::__construct('tokopress-recent-posts', '::TP:: '.__('Recent Posts', 'tokopress'), $widget_ops);
		$this->alt_option_name = 'tokopress_widget_recent_posts';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'tokopress_widget_recent_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo wp_kses_post( $cache[ $args['widget_id'] ] );
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'tokopress' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;

		$r = new WP_Query( array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) );

		if ($r->have_posts()) :
		?>
		<?php printf( '%s', $args['before_widget'] ); ?>
		<?php if ( $title ) {
			printf( '%s', $args['before_title'] . $title . $args['after_title'] );
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<?php if( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" title="">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</a>
				<?php endif; ?>
				<a class="tp-entry-title" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<span class="tp-entry-date"><?php echo get_the_date(); ?></span>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php printf( '%s', $args['after_widget'] ); ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'tokopress_widget_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tokopress_widget_recent_posts']) )
			delete_option('tokopress_widget_recent_posts');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('tokopress_widget_recent_posts', 'widget');
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tokopress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of events to show:', 'tokopress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}

class TokoPress_Widget_Upcoming_Events extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_upcoming_events', 'description' => __( "Your upcoming events with thumbnail.", 'tokopress') );
		parent::__construct('tokopress-upcoming-events', '::TP:: '.__('Upcoming Events', 'tokopress'), $widget_ops);
		$this->alt_option_name = 'tokopress_widget_upcoming_events';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'tokopress_widget_upcoming_events', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo wp_kses_post( $cache[ $args['widget_id'] ] );
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Upcoming Events', 'tokopress' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;

		$r = new WP_Query( array(
			'post_type'				=> array(Tribe__Events__Main::POSTTYPE),
			'posts_per_page'		=> $number,
			'orderby'        		=> 'event_date',
			'order'          		=> 'ASC',
			//required in 3.x
			'eventDisplay'			=> 'list'
		) );

		if ($r->have_posts()) :
		?>
		<?php printf( '%s', $args['before_widget'] ); ?>
		<?php if ( $title ) {
			printf( '%s', $args['before_title'] . $title . $args['after_title'] );
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<?php if( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" title="">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</a>
				<?php endif; ?>
				<a class="tp-entry-title" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<span class="tp-entry-date"><?php echo tribe_events_event_schedule_details(); ?></span>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php printf( '%s', $args['after_widget'] ); ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'tokopress_widget_upcoming_events', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tokopress_widget_upcoming_events']) )
			delete_option('tokopress_widget_upcoming_events');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('tokopress_widget_upcoming_events', 'widget');
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tokopress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of events to show:', 'tokopress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}


class TokoPress_Widget_Past_Events extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_past_events', 'description' => __( "Your past events with thumbnail.", 'tokopress') );
		parent::__construct('tokopress-past-events', '::TP:: '.__('Past Events', 'tokopress'), $widget_ops);
		$this->alt_option_name = 'tokopress_widget_past_events';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'tokopress_widget_past_events', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo wp_kses_post( $cache[ $args['widget_id'] ] );
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Past Events', 'tokopress' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;

		$r = new WP_Query( array(
			'post_type'				=> array(Tribe__Events__Main::POSTTYPE),
			'posts_per_page'		=> $number,
			'orderby'        		=> 'event_date',
			'order'          		=> 'ASC',
			//required in 3.x
			'eventDisplay'			=> 'past'
		) );

		if ($r->have_posts()) :
		?>
		<?php printf( '%s', $args['before_widget'] ); ?>
		<?php if ( $title ) {
			printf( '%s', $args['before_title'] . $title . $args['after_title'] );
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<?php if( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" title="">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</a>
				<?php endif; ?>
				<a class="tp-entry-title" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<span class="tp-entry-date"><?php echo tribe_events_event_schedule_details(); ?></span>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php printf( '%s', $args['after_widget'] ); ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'tokopress_widget_past_events', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['tokopress_widget_past_events']) )
			delete_option('tokopress_widget_past_events');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('tokopress_widget_past_events', 'widget');
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'tokopress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of events to show:', 'tokopress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
<?php
	}
}
