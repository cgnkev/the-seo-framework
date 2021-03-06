<?php
/**
 * Plugin Name: The SEO Framework
 * Plugin URI: https://wordpress.org/plugins/autodescription/
 * Description: An automated, advanced, accessible, unbranded and extremely fast SEO solution for any WordPress website.
 * Version: 2.7.0
 * Author: Sybre Waaijer
 * Author URI: https://cyberwire.nl/
 * License: GPLv3
 * Text Domain: autodescription
 * Domain Path: /language
 */

defined( 'ABSPATH' ) or die;

/**
 * The SEO Framework plugin
 * Copyright (C) 2015 - 2016 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

//* Debug. Not to be used on production websites as it dumps and/or disables all kinds of stuff everywhere.
//add_action( 'plugins_loaded', function() { if ( is_super_admin() ) {
	//if ( is_admin() ) {
		//define( 'THE_SEO_FRAMEWORK_DEBUG', true );
		//define( 'THE_SEO_FRAMEWORK_DEBUG_HIDDEN', true );
		//define( 'THE_SEO_FRAMEWORK_DISABLE_TRANSIENTS', true );
		//update_option( 'the_seo_framework_upgraded_db_version', '0' );
		//add_filter( 'the_seo_framework_use_object_cache', '__return_false' );
	//}
//}},0);

/**
 * CDN Cache buster. 3 to 4 point.
 * Not many caching plugins use CDN in dashboard. What a shame. Firefox does cache.
 * @since 1.0.0
 */
define( 'THE_SEO_FRAMEWORK_VERSION', '2.7.99' );

/**
 * Plugin Database version for lightweight version upgrade comparing.
 * @since 2.7.0
 */
define( 'THE_SEO_FRAMEWORK_DB_VERSION', '2710' );

/**
 * Plugin options filter.
 * @since 2.2.2
 */
define( 'THE_SEO_FRAMEWORK_SITE_OPTIONS', (string) apply_filters( 'the_seo_framework_site_options', 'autodescription-site-settings' ) );

/**
 * Plugin options filter.
 * @since 2.2.2
 */
define( 'THE_SEO_FRAMEWORK_NETWORK_OPTIONS', (string) apply_filters( 'the_seo_framework_network_settings', 'autodescription-network-settings' ) );

/**
 * Plugin term options filter.
 * @since 2.7.0
 */
define( 'THE_SEO_FRAMEWORK_TERM_OPTIONS', (string) apply_filters( 'the_seo_framework_term_options', 'autodescription-term-settings' ) );

/**
 * Plugin term options filter.
 * @since 2.7.0
 */
define( 'THE_SEO_FRAMEWORK_USER_OPTIONS', (string) apply_filters( 'the_seo_framework_user_options', 'autodescription-user-settings' ) );

/**
 * The plugin map url.
 * Used for calling browser files.
 * @since 2.2.2
 */
define( 'THE_SEO_FRAMEWORK_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * The plugin map absolute path.
 * Used for calling php files.
 * @since 2.2.2
 */
define( 'THE_SEO_FRAMEWORK_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The plugin file relative to the plugins dir.
 * @since 2.2.8
 */
define( 'THE_SEO_FRAMEWORK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The plugin file, absolute unix path.
 * @since 2.2.9
 */
define( 'THE_SEO_FRAMEWORK_PLUGIN_BASE_FILE', __FILE__ );

/**
 * The plugin views map absolute path.
 * @since 2.7.0
 */
define( 'THE_SEO_FRAMEWORK_DIR_PATH_VIEWS', THE_SEO_FRAMEWORK_DIR_PATH . 'inc/views/' );

/**
 * The plugin class map absolute path.
 * @since 2.2.9
 */
define( 'THE_SEO_FRAMEWORK_DIR_PATH_CLASS', THE_SEO_FRAMEWORK_DIR_PATH . 'inc/classes/' );

/**
 * The plugin interface map absolute path.
 * @since 2.8.0
 */
define( 'THE_SEO_FRAMEWORK_DIR_PATH_INTERFACE', THE_SEO_FRAMEWORK_DIR_PATH . 'inc/interfaces/' );

/**
 * The plugin function map absolute path.
 * @since 2.2.9
 */
define( 'THE_SEO_FRAMEWORK_DIR_PATH_FUNCT', THE_SEO_FRAMEWORK_DIR_PATH . 'inc/functions/' );

add_action( 'plugins_loaded', 'the_seo_framework_locale_init', 10 );
/**
 * Plugin locale 'autodescription'
 * File located in plugin folder autodescription/language/
 * @since 1.0.0
 */
function the_seo_framework_locale_init() {
	load_plugin_textdomain( 'autodescription', false, basename( dirname( __FILE__ ) ) . '/language/' );
}

register_activation_hook( THE_SEO_FRAMEWORK_PLUGIN_BASE_FILE, 'the_seo_framework_check_php' );
/**
 * Checks whether the server can run this plugin on activation.
 * If not, it will deactivate this plugin.
 *
 * @since 2.8.0
 */
function the_seo_framework_check_php() {

	//* Let's have some fun with teapots.
	$error = floor( time() / DAY_IN_SECONDS ) === floor( strtotime( 'first day of April ' . date( 'Y', time() ) ) / DAY_IN_SECONDS ) ? 418 : 500;

	if ( ! defined( 'PHP_VERSION_ID' ) || PHP_VERSION_ID < 50300 ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'The SEO Framework requires PHP 5.3 or later. Sorry about that!<br>
				Do you want to <a onclick="window.history.back()" href="/wp-admin/plugins.php">go back</a>?',
			'The SEO Framework &laquo; Server Requirements',
			array( 'response' => intval( $error ) )
		);
	} elseif ( $GLOBALS['wp_db_version'] < 33056 ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'The SEO Framework requires WordPress 4.3.4 or later. Sorry about that!<br>
				Do you want to <a onclick="window.history.back()" href="/wp-admin/plugins.php">go back</a>?',
			'The SEO Framework &laquo; WordPress Requirements',
			array( 'response' => intval( $error ) )
		);
	}
}

register_activation_hook( THE_SEO_FRAMEWORK_PLUGIN_BASE_FILE, 'the_seo_framework_flush_rewrite_rules_activation' );
/**
 * Add and Flush rewrite rules on plugin activation.
 *
 * @since 2.6.6
 * @since 2.7.1: 1. Now no longer reinitializes global $wp_rewrite.
 *               2. Now always listens to the preconditions of the sitemap addition.
 *               3. Now flushes the rules on shutdown.
 * @access private
 */
function the_seo_framework_flush_rewrite_rules_activation() {

	$the_seo_framework = the_seo_framework();

	if ( isset( $the_seo_framework ) ) {
		$the_seo_framework->rewrite_rule_sitemap();
		add_action( 'shutdown', 'flush_rewrite_rules' );
	}
}

register_deactivation_hook( THE_SEO_FRAMEWORK_PLUGIN_BASE_FILE, 'the_seo_framework_flush_rewrite_rules_deactivation' );
/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @since 2.6.6
 * @since 2.7.1: 1. Now no longer reinitializes global $wp_rewrite.
 *               2. Now flushes the rules on shutdown.
 * @access private
 * @global object $wp_rewrite
 */
function the_seo_framework_flush_rewrite_rules_deactivation() {
	global $wp_rewrite;

	unset( $wp_rewrite->extra_rules_top['sitemap\.xml$'] );

	add_action( 'shutdown', 'flush_rewrite_rules' );
}

/**
 * Load plugin files.
 *
 * @since 1.0.0
 * @uses THE_SEO_FRAMEWORK_DIR_PATH
 */
require_once( THE_SEO_FRAMEWORK_DIR_PATH . 'load.php' );

/**
 * Load deprecated functions.
 *
 * @since 2.7.0
 * @uses THE_SEO_FRAMEWORK_DIR_PATH_FUNCT
 */
require_once( THE_SEO_FRAMEWORK_DIR_PATH_FUNCT . 'deprecated.php' );
