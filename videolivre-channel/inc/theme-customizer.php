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
}
add_action('customize_register', 'vlchannel_customize_register');

// dynamic css
function vlchannel_customize_css() {
	?>
	<style type="text/css">
		.main-color-border {
			border-color: <?php echo get_theme_mod('main_color'); ?> !important;
		}
		.main-color-text {
			color: <?php echo get_theme_mod('main_color'); ?> !important;
		}
	</style>
	<?php
}
add_action('wp_head', 'vlchannel_customize_css');