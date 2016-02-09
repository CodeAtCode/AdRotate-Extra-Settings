<?php
/**
 * AdRotate Extra Settings
 *
 * Ultra light plugin for Wordpress that add new tiny features to AdRotate/AdRotate Pro
 *
 * @package   AdrotateExtraSettings
 * @author    Daniele 'Mte90' Scasciafratte <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      http://mte90.net
 * @copyright 2014-2016 
 *
 * @wordpress-plugin
 * Plugin Name:       Adrotate Extra Settings
 * Plugin URI:        https://github.com/Mte90/AdRotate-Extra-Settings
 * Description:       Ultra light plugin for Wordpress that add new tinys feature to AdRotate/AdRotate Pro
 * Version:           1.2.0
 * Author:            Codeat
 * Author URI:        http://mte90.net
 * Text Domain:       adrotate-extra-settings
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'AdrotateExtraSettings', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AdrotateExtraSettings', 'deactivate' ) );


/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-adrotate-extra-settings-admin.php' );
	add_action( 'plugins_loaded', array( 'AdrotateExtraSettingsAdmin', 'get_instance' ) );

}
