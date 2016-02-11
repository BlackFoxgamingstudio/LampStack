<?php

/**
 * Theme Customizer Framework
 */

/**
 * Get all registered sections
 */
function tokopress_get_customizer_sections() {
	$tk_sections = array();

	return apply_filters( 'tokopress_customizer_sections', $tk_sections );
}

/**
 * Get all registered data
 */
function tokopress_get_customizer_data() {
	$tk_colors = array();

	return apply_filters( 'tokopress_customizer_data', $tk_colors );
}

/**
 * Register Custom Sections, Settings, And Controls
 */
add_action( 'customize_register', 'tokopress_theme_customizer_register' );
function tokopress_theme_customizer_register( $wp_customize ) {
	
	// Rename Colors Sections Into General Colors
	$wp_customize->get_section( 'colors' )->title = __( 'General Colors', 'tokopress' );
	$wp_customize->get_control( 'background_color' )->priority = 0;
	$wp_customize->get_section( 'header_image' )->priority = 30;
	$wp_customize->get_section( 'background_image' )->priority = 35;


	$tk_sections 	= array();
	$tk_colors 		= array();
	$tk_sections 	= tokopress_get_customizer_sections( $tk_sections );
	$tk_colors 	= tokopress_get_customizer_data( $tk_colors );

	//create the section from array data
	foreach ( $tk_sections as $section ) {

		if ( isset ( $section['priority'] ) ) {
			$priority = $section['priority'];
		} else {
			$priority = '';
		}

		$wp_customize->add_section( $section['slug'], 
			array(
				'title'    => $section['label'],
				'priority' => $priority,
		));

	}

	//create the componen from array data
	foreach ( $tk_colors as $color ) {
		
		if ( isset ( $color['transport'] ) ) {
			$transport = $color['transport'];
		} else {
			$transport = 'postMessage';
		}

		if ( isset ( $color['priority'] ) ) {
			$priority = $color['priority'];
		} else {
			$priority = '';
		}

		// Define each customizer type 
		switch ( $color['type'] ) {
			
			case 'color':

				$wp_customize->add_setting( $color['slug'], 
					array( 
						'default' 			=> $color['default'], 
						'type' 				=> 'theme_mod', 
						'transport'			=> $transport, 
						'capability' 		=> 'edit_theme_options',
						'sanitize_callback'	=> 'esc_attr'
					) );
				
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $color['slug'], 
					array( 
						'label' 		=> $color['label'], 
						'section' 		=> $color['section'], 
						'priority'		=> $priority, 
						'settings' 		=> $color['slug'] ) 
					) );
				
				break;
			
			case 'select' :

				$wp_customize->add_setting( $color['slug'], 
					array(
						'default' 			=> $color['default'],
						'type' 				=> 'theme_mod', 
						'transport'   		=> $transport,
						'capability' 		=> 'edit_theme_options',
						'sanitize_callback'	=> 'esc_attr'
					) );
				
				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 
					$color['slug'], 
					array( 
						'label' 		=> $color['label'], 
						'section' 		=> $color['section'],
						'default' 		=> $color['default'],
						'priority'		=> $priority,
						'settings' 		=> $color['slug'], 
						'choices'		=> $color['choices'], 
						'type'			=> 'select'
						)
					));

				break;

			case 'select_font' :

				$wp_customize->add_setting( $color['slug'], 
					array(
						'default' 			=> $color['default'],
						'type' 				=> 'theme_mod', 
						'transport'   		=> $transport,
						'capability' 		=> 'edit_theme_options',
						'sanitize_callback'	=> 'esc_attr'
					) );
				
				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 
					$color['slug'], 
					array( 
						'label' 		=> $color['label'], 
						'section' 		=> $color['section'],
						'default' 		=> $color['default'],
						'priority'		=> $priority,
						'settings' 		=> $color['slug'], 
						'choices'		=> tokopress_customizer_library_get_font_choices(), 
						'type'			=> 'select'
						)
					));

				break;

			case 'text':

				$wp_customize->add_setting( $color['slug'] , 
					array(
						'default'			=> $color['default'],
						'type'				=> 'theme_mod',
						'transport'   		=> $transport,
						'capability'		=> 'edit_theme_options',
						'sanitize_callback'	=> 'esc_attr'
				) );

				$wp_customize->add_control( $color['slug'] , 
					array(
						'label'		=> $color['label'],
						'section'	=> $color['section'],
						'priority'	=> $priority,
						'settings'	=> $color['slug'],
						'type'		=> 'text'
					) );

				break;

			case 'textarea' :

				$wp_customize->add_setting( $color['slug'], 
					array(
				    	'default'			=> $color['default'],
				    	'sanitize_callback'	=> 'esc_attr'
				) );
				 
				$wp_customize->add_control( new Tokopress_Customize_Textarea_Control( $wp_customize, 
					$color['slug'], 
					array(
					    'label'   		=> $color['label'],
					    'section' 		=> $color['section'],
						'priority'		=> $priority, 
					    'settings'		=> $color['slug'],
				) ) );

				break;

			case 'images' :

				$wp_customize->add_setting( $color['slug'], 
					array( 
						'default' 			=> $color['default'], 
						'capability' 		=> 'edit_theme_options', 
						'type' 				=> 'theme_mod',
						'sanitize_callback'	=> 'esc_attr'
						));   

				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
					$color['slug'], 
					array( 
						'label' 		=> $color['label'], 
						'section' 		=> $color['section'],
						'priority'		=> $priority, 
						'settings' 		=> $color['slug']
						 )));

				break;

			default:
				
				break;
		}

	}
	
}

/**
 * Used by hook: 'customize_preview_init'
 *
 * @see add_action( 'customize_preview_init', $func )
 */
add_action( 'customize_preview_init', 'tokopress_customizer_live_preview' , 1 );

function tokopress_customizer_live_preview() {
	
	$tk_colors 	= array();
	$tk_colors 	= tokopress_get_customizer_data( $tk_colors );

	wp_enqueue_script( 'customizer-preview', get_template_directory_uri().'/inc/customizer/js/customizer-preview.js', array( 'jquery', 'customize-preview' ), '', true );
	wp_localize_script(	'customizer-preview', 'custStyle', $tk_colors );

 }

/**
 * Sanitize Print To Head
 */
add_action( 'tokopress_custom_styles', 'tokopress_customizer_css', 10 );
function tokopress_customizer_css() { 
	
	$tk_colors 		= array();
	$tk_colors 	= tokopress_get_customizer_data( $tk_colors );

	$style = '';
	

	foreach ( $tk_colors as $color ) {

		$selector = ! empty( $color['selector'] ) ? $color['selector'] : '';
		$property = ! empty( $color['property'] ) ? $color['property'] : '';
		$propertyadd = ! empty( $color['propertyadd'] ) ? $color['propertyadd'] : '';

		$selector2 = ! empty( $color['selector2'] ) ? $color['selector2'] : '';
		$property2 = ! empty( $color['property2'] ) ? $color['property2'] : '';
		$propertyadd2 = ! empty( $color['propertyadd2'] ) ? $color['propertyadd2'] : '';

		$value	= get_theme_mod( $color['slug'] );

		if ( $color['type'] == 'color' ) {
			if ( isset( $value ) && ! empty( $value ) ) {
				if ( $selector && $property )
					$style .=  $selector. " { $property : $value $propertyadd; } ";
				if ( $selector2 && $property2 )
					$style .=  $selector2. " { $property2 : $value $propertyadd2; } ";
			}
		} 
		else if ( $color['type'] == 'images' ) {
			if ( isset( $value ) && ! empty( $value ) ) {
				if ( $selector && $property )
					$style .=  $selector. " { $property : url('".$value."') $propertyadd; } ";
			}
		} 
		elseif ( $color['type'] == 'select_font' ) {
			if ( isset( $value ) && ! empty( $value ) ) {
				if ( isset( $color['default'] ) && $color['default'] && $value != $color['default'] ) {
					if ( $selector && $property )
						$style .=  $selector. " { $property : \"$value\" $propertyadd; } ";
				}
			}
		} 
	}
	
	if ( trim( $style ) )
		printf( '%s', trim( $style ) );

}

/**
 * Enqueue Google Font Base on Customizer Data
 **/
add_action( 'wp_enqueue_scripts', 'tokopress_customizer_font_output' );
function tokopress_customizer_font_output() {

	$tk_sections 	= array();
	$tk_colors 		= array();
	$tk_sections 	= tokopress_get_customizer_sections( $tk_sections );
	$tk_colors 		= tokopress_get_customizer_data( $tk_colors );

	$loaded_font = '';
	
	foreach ( $tk_colors as $color ) {

		if ( $color['type'] == 'select_font' ) {
			$newvalue	= get_theme_mod( $color['slug'] );
			if ( !$newvalue && isset( $color['default'] ) && $color['default'] ) {
				$newvalue = $color['default'];
			}
			if ( isset( $newvalue ) && ! empty( $newvalue ) ) {
				$get_selected_font = str_replace(' ', '+', $newvalue ).':400,700';
				$loaded_font .= ( ! $loaded_font ) ? $get_selected_font : '|'.$get_selected_font;
			}
		}
		
	} 
	if ( trim( $loaded_font ) ) 
		wp_enqueue_style( 'googlefonts-customizer', '//fonts.googleapis.com/css?family='.trim( $loaded_font ) );

}

if ( class_exists( 'WP_Customize_Control' ) ) :
	class Tokopress_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
	 
		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}
endif;
