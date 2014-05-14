<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package   AdrotateExtraSettings
 * @author    Daniele 'Mte90' Scasciafratte <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 
 */
// If uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

delete_option( 'adrotate-extra-settings' );
