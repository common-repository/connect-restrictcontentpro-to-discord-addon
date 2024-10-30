<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/includes
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Restrictcontentpro_Discord_Addon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Restrictcontentpro_Discord_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'RESTRICTCONTENTPRO_DISCORD_ADDON_VERSION' ) ) {
			$this->version = RESTRICTCONTENTPRO_DISCORD_ADDON_VERSION;
		} else {
			$this->version = '1.0.5';
		}
		$this->plugin_name = 'restrictcontentpro-discord-addon';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Restrictcontentpro_Discord_Addon_Loader. Orchestrates the hooks of the plugin.
	 * - Restrictcontentpro_Discord_Addon_i18n. Defines internationalization functionality.
	 * - Restrictcontentpro_Discord_Addon_Admin. Defines all hooks for the admin area.
	 * - Restrictcontentpro_Discord_Addon_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all methods that help to schedule actions.
		 */
		require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'includes/libraries/action-scheduler/action-scheduler.php';

		/**
			 * Define common functions.
			 */
			require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-restrictcontentpro-discord-addon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-restrictcontentpro-discord-addon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-restrictcontentpro-discord-addon-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-restrictcontentpro-discord-addon-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-restrictcontentpro-discord-addon-admin-notices.php';

		$this->loader = new Restrictcontentpro_Discord_Addon_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Restrictcontentpro_Discord_Addon_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Restrictcontentpro_Discord_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Restrictcontentpro_Discord_Addon_Admin( $this->get_plugin_name(), $this->get_version(), Restrictcontentpro_Discord_Addon_Public::get_restrictcontentpro_discord_public_instance( $this->plugin_name, $this->version ) );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu_for_restricted_content_discord', 11 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'ets_restrictcontentpro_discord_connect_bot' );
		$this->loader->add_action( 'admin_post_restrictcontentpro_discord_general_settings', $plugin_admin, 'ets_restrictcontentpro_discord_general_settings' );
		$this->loader->add_action( 'admin_post_restrictcontentpro_discord_role_mapping', $plugin_admin, 'ets_restrictcontentpro_discord_role_mapping' );
		$this->loader->add_action( 'admin_post_restrictcontentpro_discord_advance_settings', $plugin_admin, 'ets_restrictcontentpro_discord_advance_settings' );
		$this->loader->add_action( 'admin_post_restrictcontentpro_discord_save_appearance_settings', $plugin_admin, 'ets_restrictcontentpro_discord_save_appearance_settings' );
		$this->loader->add_action( 'admin_post_restrictcontentpro_discord_send_support_mail', $plugin_admin, 'ets_restrictcontentpro_discord_send_support_mail' );
		$this->loader->add_action( 'wp_ajax_restrictcontentpro_load_discord_roles', $plugin_admin, 'ets_restrictcontentpro_load_discord_roles' );
		$this->loader->add_action( 'wp_ajax_restrictcontentpro_discord_clear_logs', $plugin_admin, 'ets_restrictcontentpro_discord_clear_logs' );
		$this->loader->add_action( 'wp_ajax_restrictcontentpro_discord_member_table_run_api', $plugin_admin, 'ets_restrictcontentpro_discord_member_table_run_api' );
		$this->loader->add_action( 'wp_ajax_restrictcontentpro_discord_update_redirect_url', $plugin_admin, 'ets_restrictcontentpro_discord_update_redirect_url' );
		$this->loader->add_action( 'rcp_action_edit_membership', $plugin_admin, 'ets_restrictcontentpro_discord_as_schdule_job_restrictcontentpro_membership_delete' );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_complete_transaction', $plugin_admin, 'ets_restrictcontentpro_discord_as_handler_restrictcontentpro_complete_transaction', 10, 2 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_schedule_delete_role', $plugin_admin, 'ets_restrictcontentpro_discord_as_handler_delete_memberrole', 10, 3 );
		$this->loader->add_action( 'rcp_transition_membership_status', $plugin_admin, 'ets_restrictcontentpro_rcp_transition_membership_status', 10, 3 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_expiry', $plugin_admin, 'ets_restrictcontentpro_discord_as_handler_restrictcontentpro_expiry', 10, 2 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_cancelled', $plugin_admin, 'ets_restrictcontentpro_discord_as_handler_restrictcontentpro_cancelled', 10, 2 );
		$this->loader->add_action( 'rcp_send_expiring_soon_notice', $plugin_admin, 'ets_restrictcontentpro_discord_send_expiration_warning_dm' );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_send_dm', $this, 'ets_restrictcontentpro_discord_handler_send_dm', 10, 3 );
		$this->loader->add_action( 'rcp_update_payment_status', $plugin_admin, 'ets_restrictcontentpro_rcp_update_payment_status', 10, 2 );
		$this->loader->add_filter( 'rcp_customers_list_table_columns', $plugin_admin, 'ets_restrictcontentpro_rcp_members_page_table_column' );
		$this->loader->add_filter( 'rcp_customers_list_table_column_col_restrictcontentpro_discord', $plugin_admin, 'ets_restrictcontentpro_rcp_members_page_table_row_discord', 10, 2 );
		$this->loader->add_filter( 'rcp_customers_list_table_column_col_restrictcontentpro_joined_date', $plugin_admin, 'ets_restrictcontentpro_rcp_members_page_table_row_joined_date', 10, 2 );
		// $this->loader->add_action( 'rcp_remove_level', $plugin_admin, 'ets_restrictcontentpro_discord_as_schedule_job_membership_level_deleted' );
		$this->loader->add_action( 'rcp_action_delete_customer', $plugin_admin, 'ets_restrictcontentpro_discord_as_schedule_job_membership_customer_deleted' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Restrictcontentpro_Discord_Addon_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'ets_restrictcontentpro_discord_discord_api_callback' );
		$this->loader->add_shortcode( 'ets_restrictcontentpro_discord', $plugin_public, 'ets_restrictcontentpro_discord_add_connect_button' );
		$this->loader->add_action( 'rcp_profile_editor_after', $plugin_public, 'ets_restrictcontentpro_show_discord_button' );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_handle_add_member_to_guild', $plugin_public, 'ets_restrictcontentpro_discord_as_handler_add_member_to_guild', 10, 4 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_schedule_delete_member', $plugin_public, 'ets_restrictcontentpro_discord_as_handler_delete_member_from_guild', 10, 3 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_send_welcome_dm', $this, 'ets_restrictcontentpro_discord_handler_send_dm', 10, 3 );
		$this->loader->add_action( 'ets_restrictcontentpro_discord_as_schedule_member_put_role', $plugin_public, 'ets_restrictcontentpro_discord_as_handler_put_memberrole', 10, 3 );
		$this->loader->add_action( 'wp_ajax_restrictcontentpro_disconnect_from_discord', $plugin_public, 'ets_restrictcontentpro_disconnect_from_discord' );
		$this->loader->add_filter( 'kses_allowed_protocols', $plugin_public, 'ets_restrictcontentpro_discord_allow_data_protocol' );
	}

	/**
	 * Discord DM a member using bot.
	 *
	 * @param INT    $user_id
	 * @param ARRAY  $active_membership
	 * @param STRING $type (warning|expired)
	 */
	public function ets_restrictcontentpro_discord_handler_send_dm( $user_id, $active_membership, $type = 'warning' ) {
		$discord_user_id   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$ets_restrictcontentpro_discord_expiration_warning_message = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_expiration_warning_message' ) ) );
		$ets_restrictcontentpro_discord_expiration_expired_message = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_expiration_expired_message' ) ) );
		$ets_restrictcontentpro_discord_welcome_message            = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_welcome_message' ) ) );
		$ets_restrictcontentpro_discord_cancel_message             = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_cancel_message' ) ) );
		// Check if DM channel is already created for the user.
		$user_dm = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_dm_channel', true );

		if ( ! isset( $user_dm['id'] ) || $user_dm == false || empty( $user_dm ) ) {
			$this->ets_restrictcontentpro_discord_create_member_dm_channel( $user_id );
			$user_dm       = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_dm_channel', true );
			$dm_channel_id = $user_dm['id'];
		} else {
			$dm_channel_id = $user_dm['id'];
		}

		if ( $type == 'warning' ) {
			$message = ets_restrictcontentpro_discord_get_formatted_dm( $user_id, $active_membership, $ets_restrictcontentpro_discord_expiration_warning_message );
		}
		if ( $type == 'expired' ) {
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_expired_dm_for_' . $active_membership['product_id'], true );
			$message = ets_restrictcontentpro_discord_get_formatted_dm( $user_id, $active_membership, $ets_restrictcontentpro_discord_expiration_expired_message );
		}
		if ( $type == 'welcome' ) {
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_welcome_dm_for_' . $active_membership['product_id'], true );
			$message = ets_restrictcontentpro_discord_get_formatted_dm( $user_id, $active_membership, $ets_restrictcontentpro_discord_welcome_message );
		}
		if ( $type == 'cancel' ) {
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_cancel_dm_for_' . $active_membership['product_id'], true );
			$message = ets_restrictcontentpro_discord_get_formatted_dm( $user_id, $active_membership, $ets_restrictcontentpro_discord_cancel_message );
		}

		$creat_dm_url = RESTRICT_CONTENT_DISCORD_API_URL . '/channels/' . $dm_channel_id . '/messages';
		$dm_args      = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => wp_json_encode(
				array(
					'content' => sanitize_text_field( trim( wp_unslash( $message ) ) ),
				)
			),
		);
		$dm_response  = wp_remote_post( $creat_dm_url, $dm_args );
		ets_restrictcontentpro_discord_log_api_response( $user_id, $creat_dm_url, $dm_args, $dm_response );
		$dm_response_body = json_decode( wp_remote_retrieve_body( $dm_response ), true );
		if ( ets_restrictcontentpro_discord_check_api_errors( $dm_response ) ) {
			res_write_api_response_logs( $dm_response_body, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_restrictcontentpro_discord_send_dm' );
		}
	}

	/**
	 * Create DM channel for a give user_id
	 *
	 * @param INT $user_id
	 * @return MIXED
	 */
	public function ets_restrictcontentpro_discord_create_member_dm_channel( $user_id ) {
		$discord_user_id       = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$create_channel_dm_url = RESTRICT_CONTENT_DISCORD_API_URL . '/users/@me/channels';
		$dm_channel_args       = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => wp_json_encode(
				array(
					'recipient_id' => $discord_user_id,
				)
			),
		);

		$created_dm_response = wp_remote_post( $create_channel_dm_url, $dm_channel_args );
		ets_restrictcontentpro_discord_log_api_response( $user_id, $create_channel_dm_url, $dm_channel_args, $created_dm_response );
		$response_arr = json_decode( wp_remote_retrieve_body( $created_dm_response ), true );

		if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
			// check if there is error in create dm response.
			if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
				res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( ets_restrictcontentpro_discord_check_api_errors( $created_dm_response ) ) {
					// this should be catch by Action schedule failed action.
					throw new Exception( 'Failed in function ets_restrictcontentpro_discord_create_member_dm_channel' );
				}
			} else {
				update_user_meta( $user_id, '_ets_restrictcontentpro_discord_dm_channel', $response_arr );
			}
		}
		return $response_arr;
	}

	/**
	 * Retrieve the Discord Logo.
	 */
	public static function get_discord_logo_white() {
		$img  = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'public/images/discord-logo-white.svg' );
		$data = base64_encode( $img );
		return '<img class="ets-discord" src="data:image/svg+xml;base64,' . $data . '" />';
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Restrictcontentpro_Discord_Addon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
