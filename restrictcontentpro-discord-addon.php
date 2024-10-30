<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Restrictcontentpro_Discord_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Connect Restrictcontentpro to Discord AddOn
 * Plugin URI:        https://www.expresstechsoftwares.com/restrictcontentpro-discord-addon
 * Description:       Connect your Restrict Content Pro site to your discord server, enable your members to be part of your discord community.
 * Version:           1.0.5
 * Author:            ExpressTech Softwares Solutions Pvt Ltd
 * Author URI:        https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       restrictcontentpro-discord-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RESTRICTCONTENTPRO_DISCORD_ADDON_VERSION', '1.0.5' );

/**
 * Discord Api url
 */
define( 'RESTRICT_CONTENT_DISCORD_API_URL', 'https://discord.com/api/v10/' );

/**
 * Discord Bot Permissions
 */
define( 'RESTRICT_CONTENT_DISCORD_BOT_PERMISSIONS', 8 );

/**
 * Discord api call scopes
 */
define( 'RESTRICT_CONTENT_DISCORD_OAUTH_SCOPES', 'identify email guilds guilds.join' );

/**
 * Define group name for action scheduler actions
 */
define( 'RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME', 'ets-restrict-content-discord' );

/**
 * Define plugin directory path
 */
define( 'RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Define plugin directory url
 */
define( 'RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Follwing response codes not cosider for re-try API calls.
 */
define( 'ETS_RESTRICT_CONTENT_DISCORD_DONOT_RETRY_THESE_API_CODES', array( 0, 10003, 50033, 10004, 50025, 10013, 10011 ) );

/**
 * Define plugin directory url
 */
define( 'ETS_RESTRICT_CONTENT_DISCORD_DONOT_RETRY_HTTP_CODES', array( 400, 401, 403, 404, 405, 502 ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-restrictcontentpro-discord-addon-activator.php
 */
function activate_restrictcontentpro_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-restrictcontentpro-discord-addon-activator.php';
	Restrictcontentpro_Discord_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-restrictcontentpro-discord-addon-deactivator.php
 */
function deactivate_restrictcontentpro_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-restrictcontentpro-discord-addon-deactivator.php';
	Restrictcontentpro_Discord_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_restrictcontentpro_discord_addon' );
register_deactivation_hook( __FILE__, 'deactivate_restrictcontentpro_discord_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-restrictcontentpro-discord-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_restrictcontentpro_discord_addon() {

	$plugin = new Restrictcontentpro_Discord_Addon();
	$plugin->run();

}
run_restrictcontentpro_discord_addon();
