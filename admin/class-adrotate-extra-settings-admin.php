<?php

/**
 * Adrotate Extra Settings
 *
 * @package   AdrotateExtraSettingsAdmin
 * @author    Daniele 'Mte90' Scasciafratte <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      http://mte90.net
 * @copyright 2014 
 */

/**
 *
 * @package AdrotateExtraSettingsAdmin
 * @author  Daniele 'Mte90' Scasciafratte <mte90net@gmail.com>
 */
class AdrotateExtraSettingsAdmin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {
        global $pagenow;
        $this->plugin_slug = 'adrotate-extra-settings';

        // Add the settings field
        add_action( 'admin_init', array( $this, 'adrotate_extra_settings_form' ) );
        // Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

        if ( $pagenow === 'admin.php' ) {
            if ( $_GET[ 'page' ] === 'adrotate-ads' ) {
                $setting = ( array ) get_option( $this->plugin_slug );
                if ( isset( $_GET[ 'view' ] ) && ($_GET[ 'view' ] === 'addnew' || $_GET[ 'view' ] === 'edit' ) ) {
                    if ( isset( $setting[ 'examples' ] ) && !empty( $setting[ 'examples' ] ) ) {
                        add_action( 'admin_head', array( $this, 'add_examples' ) );
                    }
                    if ( isset( $setting[ 'hide_usage' ] ) && !empty( $setting[ 'hide_usage' ] ) ||
                            isset( $setting[ 'hide_geolocation' ] ) && !empty( $setting[ 'hide_geolocation' ] ) ||
                            isset( $setting[ 'hide_timeframe' ] ) && !empty( $setting[ 'hide_timeframe' ] ) ) {
                        add_action( 'admin_head', array( $this, 'hide_section_js' ) );
                    }
                } else if ( !isset( $_GET[ 'view' ] ) ) {
                    if ( isset( $setting[ 'sortable' ] ) ) {
                        add_action( 'admin_head', array( $this, 'add_sortable' ) );
                    }
                }
            } else if ( $_GET[ 'page' ] === 'adrotate-groups' || $_GET[ 'page' ] === 'adrotate-schedules') {
                $setting = ( array ) get_option( $this->plugin_slug );
                if ( isset( $setting[ 'sortable' ] ) ) {
                    add_action( 'admin_head', array( $this, 'add_sortable' ) );
                }
            } 
        }
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        $this->plugin_screen_hook_suffix = add_submenu_page( 'adrotate', __( 'Adrotate Extra Settings', $this->plugin_slug ), __( 'Extra Settings', $this->plugin_slug ), 'manage_options', $this->plugin_slug, array( $this, 'display_plugin_admin_page' ) );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'views/admin.php' );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {

        return array_merge(
                array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
                ), $links
        );
    }

    /**
     * Intiliaze all the field for the setting page.
     *
     * @since    1.0.0
     */
    function adrotate_extra_settings_form() {

        add_settings_section(
                $this->plugin_slug, __( 'Tweak', $this->plugin_slug ), '__return_false', $this->plugin_slug
        );

        add_settings_field(
                $this->plugin_slug . '_custom_examples', __( 'This add custom examples in the box for the ads.<br> One rule for line, empty for disable it', $this->plugin_slug ), array( $this, 'field_examples' ), $this->plugin_slug, $this->plugin_slug
        );

        add_settings_field(
                $this->plugin_slug . '_custom_sortable', __( 'Enable sorting for table list of Ads and Groups', $this->plugin_slug ), array( $this, 'field_sortable' ), $this->plugin_slug, $this->plugin_slug
        );

        add_settings_field(
                $this->plugin_slug . '_hide_usage', __( 'Hide usage section', $this->plugin_slug ), array( $this, 'hide_usage' ), $this->plugin_slug, $this->plugin_slug
        );
        
        add_settings_field(
                $this->plugin_slug . 'hide_geolocation', __( 'Hide geolocation section', $this->plugin_slug ), array( $this, 'hide_geolocation' ), $this->plugin_slug, $this->plugin_slug
        );
        
        add_settings_field(
                $this->plugin_slug . 'hide_advanced', __( 'Hide advanced section', $this->plugin_slug ), array( $this, 'hide_advanced' ), $this->plugin_slug, $this->plugin_slug
        );

        register_setting( $this->plugin_slug, $this->plugin_slug );
    }

    /**
     * Custom examples
     *
     * @since    1.0.0
     */
    function field_examples() {
        $setting = ( array ) get_option( $this->plugin_slug );

        if ( !isset( $setting[ 'examples' ] ) ) {
            $setting[ 'examples' ] = '<img src="%image%" />';
        }

        echo '<textarea name="' . $this->plugin_slug . '[examples]">' . esc_attr( $setting[ 'examples' ] ) . '</textarea>';
    }

    /**
     * Sortable Table
     *
     * @since    1.0.0
     */
    function field_sortable() {
        $setting = ( array ) get_option( $this->plugin_slug );

        if ( !isset( $setting[ 'sortable' ] ) ) {
            $setting[ 'sortable' ] = false;
        }

        echo '<input type="checkbox" name="' . $this->plugin_slug . '[sortable]" ' . checked( $setting[ 'sortable' ], 'on', false ) . ' />';
    }

    /**
     * Hide usage
     *
     * @since    1.0.0
     */
    function hide_usage() {
        $setting = ( array ) get_option( $this->plugin_slug );

        if ( !isset( $setting[ 'hide_usage' ] ) ) {
            $setting[ 'hide_usage' ] = false;
        }

        echo '<input type="checkbox" name="' . $this->plugin_slug . '[hide_usage]" ' . checked( $setting[ 'hide_usage' ], 'on', false ) . ' />';
    }
    
    /**
     * Hide usage
     *
     * @since    1.0.0
     */
    function hide_geolocation() {
        $setting = ( array ) get_option( $this->plugin_slug );

        if ( !isset( $setting[ 'hide_geolocation' ] ) ) {
            $setting[ 'hide_geolocation' ] = false;
        }

        echo '<input type="checkbox" name="' . $this->plugin_slug . '[hide_geolocation]" ' . checked( $setting[ 'hide_geolocation' ], 'on', false ) . ' />';
    }
    
    /**
     * Hide advanced
     *
     * @since    1.1.0
     */
    function hide_advanced() {
        $setting = ( array ) get_option( $this->plugin_slug );

        if ( !isset( $setting[ 'hide_advanced' ] ) ) {
            $setting[ 'hide_advanced' ] = false;
        }

        echo '<input type="checkbox" name="' . $this->plugin_slug . '[hide_advanced]" ' . checked( $setting[ 'hide_advanced' ], 'on', false ) . ' />';
    }

    /**
     * Add custom examples
     *
     * @since    1.0.0
     */
    public function add_examples() {
        $setting = ( array ) get_option( $this->plugin_slug );
        echo '<script>' . "\n";
        echo 'jQuery(function() {' . "\n";
        $setting[ 'examples' ] = explode( '\n', $setting[ 'examples' ] );
        foreach ( $setting[ 'examples' ] as $value ) {
            echo "jQuery('table.widefat:first tr:nth-child(2n) a[onclick]:last').parent().parent().after('<p><em><a onclick=\"textatcursor(\'adrotate_bannercode\',\'" . htmlspecialchars( $value ) . "\');return false;\" href=\"#\">" . htmlspecialchars( $value ) . "</a></em></p>')\n";
        }
        echo '});' . "\n";
        echo '</script>' . "\n";
    }

    /**
     * Add sortable table
     *
     * @since    1.0.0
     */
    public function add_sortable() {
        //$setting = ( array ) get_option( $this->plugin_slug );
        echo '<link rel="stylesheet" type="text/css" media="all"  href="' . plugins_url( 'assets/tablesorter/style.css', __FILE__ ) . '"/>' . "\n";
        echo '<script src="' . plugins_url( 'assets/tablesorter/jquery.tablesorter.min.js', __FILE__ ) . '"></script>' . "\n";
        echo '<script>' . "\n";
        echo 'jQuery(function() {' . "\n";
        echo 'jQuery("table.widefat:not(:last)").addClass("tablesorter").tablesorter();' . "\n";
        echo '});' . "\n";
        echo '</script>' . "\n";
    }

    /**
     * Hide usage
     *
     * @since    1.0.0
     */
    public function hide_section_js() {
        $setting = ( array ) get_option( $this->plugin_slug );
        echo '<script>' . "\n";
        echo 'jQuery(function() {' . "\n";
        if ( isset( $setting[ 'hide_usage' ] ) ) {
            echo "jQuery('h3:contains(\"Usage\")').hide().next().hide().next().hide().next().hide().next().hide().next().hide();\n";
        }
        if ( isset( $setting[ 'hide_geolocation' ] ) ) {
            echo "jQuery('h3:contains(\"Geo Targeting in AdRotate Pro\"), h3:contains(\"Geo Targeting\")').hide().next().hide().next().hide();\n";
        }
        if ( isset( $setting[ 'hide_advanced' ] ) ) {
            echo "jQuery('h3:contains(\"Advanced\")').hide().next().hide().next().hide().next().hide();\n";
        }
        echo '});' . "\n";
        echo '</script>' . "\n";
    }

}
