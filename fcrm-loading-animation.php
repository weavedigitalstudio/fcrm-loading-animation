<?php
/**
 * Plugin Name:       FirehawkCRM Tributes - Tribute Loading Animation
 * Plugin URI:        https://github.com/weavedigitalstudio/fcrm-loading-animation
 * Description:       Adds a loading spinner animation while an individual tribute is loaded with the FireHawk CRM Tributes plugin.
 * Version:           0.1.1
 * Author:            Weave Digital Studio, Gareth Bissland
 * License: MIT
 * License URI: 	  https://opensource.org/licenses/MIT
 * GitHub Plugin URI: weavedigitalstudio/fcrm-loading-animation
 * Primary Branch:    main
 * Requires at least: 6.0
 * Requires PHP:      7.2
 */

/*
 Changelog:
 Version 0.1.1
 - Added colour picker for spinner colour.
  */


// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

// Create the settings page under "Settings"
function fcrm_loading_animation_settings_page() {
	add_options_page(
		'Firehawk CRM Loading Animation Settings',
		'Firehawk CRM Loading Animation',
		'manage_options',
		'fcrm-loading-animation-settings',
		'fcrm_loading_animation_settings_callback'
	);
}
add_action( 'admin_menu', 'fcrm_loading_animation_settings_page' );

// Callback function for the settings page
function fcrm_loading_animation_settings_callback() {
	?>
	<div class="wrap">
		<h1>Firehawk CRM Loading Animation Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'fcrm-loading-animation-settings' );
			do_settings_sections( 'fcrm-loading-animation-settings' );
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Grid Post ID:</th>
					<td><input type="text" name="fcrm_loading_animation_page_id" value="<?php echo esc_attr( get_option( 'fcrm_loading_animation_page_id' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Spinner Colour:</th>
					<td><input type="text" name="fcrm_loading_animation_spinner_color" value="<?php echo esc_attr( get_option( 'fcrm_loading_animation_spinner_color', '#b1a357' ) ); ?>" class="color-picker" /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Initialize settings fields
function fcrm_loading_animation_settings_init() {
	register_setting( 'fcrm-loading-animation-settings', 'fcrm_loading_animation_page_id' );
	register_setting( 'fcrm-loading-animation-settings', 'fcrm_loading_animation_spinner_color' );

	add_settings_section(
		'fcrm_loading_animation_section',
		'Firehawk CRM Animation Settings',
		null,
		'fcrm-loading-animation-settings'
	);
}
add_action( 'admin_init', 'fcrm_loading_animation_settings_init' );

// Enqueue the admin scripts for the color picker
function fcrm_loading_animation_enqueue_admin_scripts( $hook_suffix ) {
	if ( $hook_suffix === 'settings_page_fcrm-loading-animation-settings' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'fcrm-loading-animation-color-picker', plugins_url( 'js/color-picker-init.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
}
add_action( 'admin_enqueue_scripts', 'fcrm_loading_animation_enqueue_admin_scripts' );

// Enqueue front-end scripts and styles
function fcrm_loading_animation_enqueue_scripts() {
	$page_id = get_option( 'fcrm_loading_animation_page_id' );
	if ( is_page( $page_id ) ) {
		wp_enqueue_script( 'fcrm-loading-animation-script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'fcrm-loading-animation-style', plugins_url( 'css/style.css', __FILE__ ) );
	}
}
add_action( 'wp_enqueue_scripts', 'fcrm_loading_animation_enqueue_scripts' );

// Output inline CSS for the spinner color
function fcrm_loading_animation_inline_css() {
	$spinner_color = get_option( 'fcrm_loading_animation_spinner_color', '#b1a357' ); // Default to #b1a357 if not set
	echo "<style>
		.loading-animation::after {
			border-top: 4px solid {$spinner_color};
		}
	</style>";
}
add_action( 'wp_head', 'fcrm_loading_animation_inline_css' );