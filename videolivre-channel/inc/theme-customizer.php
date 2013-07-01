<?php
/**
 * Theme customizer
 */

function vlchannel_customize_register($wp_customize) {

	// custom main color
	$wp_customize->add_setting('main_color' , array(
		'default'     => '#ff0000',
		'transport'   => 'refresh',
	));

	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize, 'main_color', array(
			'label'		=> __('Channel color', 'videolivre-channel'),
			'section'	=> 'colors',
			'settings'	=> 'main_color',
		))
	);

	// custom header background color
	$wp_customize->add_setting('header_background_color' , array(
		'default'     => 'transparent',
		'transport'   => 'refresh',
	));

	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize, 'header_background_color', array(
			'label'		=> __('Header background color', 'videolivre-channel'),
			'section'	=> 'header_image',
			'settings'	=> 'header_background_color',
		))
	);

	// logo upload
	$wp_customize->add_setting('logo_image' , array(
		'default'     => false,
		'transport'   => 'refresh',
	));

	$wp_customize->add_control(
		new WP_Customize_Image_Control($wp_customize, 'logo_image', array(
			'label'		=> __('Website Logo', 'videolivre-channel'),
			'section'	=> 'header_image',
			'settings'	=> 'logo_image',
		))
	);

}
add_action('customize_register', 'vlchannel_customize_register');

// dynamic css
function vlchannel_customize_css() {
	?>
	<style type="text/css">
		.site-background {
			background: #<?php echo get_theme_mod('background_color'); ?>;
		}
		.main-color-border {
			border-color: <?php echo get_theme_mod('main_color'); ?> !important;
		}
		.main-color-text {
			color: <?php echo get_theme_mod('main_color'); ?> !important;
		}
		#masthead hgroup,
		.site-header {
			background-color: <?php echo get_theme_mod('header_background_color'); ?>;
		}
	</style>
	<?php
}
add_action('wp_head', 'vlchannel_customize_css');