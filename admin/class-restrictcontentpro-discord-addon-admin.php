<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/admin
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Restrictcontentpro_Discord_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Static property to define log file name
	 *
	 * @param None
	 * @return string $log_file_name
	 */
	public static $log_file_name = 'discord_api_logs.txt';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of Restrictcontentpro_Discord_Addon_Public class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Restrictcontentpro_Discord_Addon_Public
	 */
	private $restrictcontentpro_discord_public_instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 * @param      object $restrictcontentpro_discord_public_instance The Restrictcontentpro Public Class instance.
	 */
	public function __construct( $plugin_name, $version, $restrictcontentpro_discord_public_instance ) {

		$this->plugin_name                                = $plugin_name;
		$this->version                                    = $version;
		$this->restrictcontentpro_discord_public_instance = $restrictcontentpro_discord_public_instance;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Restrictcontentpro_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Restrictcontentpro_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min_css = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_style( $this->plugin_name . 'restrictcontent_tabs_css', plugin_dir_url( __FILE__ ) . 'css/skeletabs.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/restrictcontentpro-discord-addon-admin' . $min_css . '.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'res_font_awesome_css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Restrictcontentpro_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Restrictcontentpro_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min_js = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_script( $this->plugin_name . 'restrictcontent_tabs_js', plugin_dir_url( __FILE__ ) . 'js/skeletabs.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/restrictcontentpro-discord-addon-admin' . $min_js . '.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), $this->version, false );

		$script_params = array(
			'admin_ajax'                           => admin_url( 'admin-ajax.php' ),
			'permissions_const'                    => RESTRICT_CONTENT_DISCORD_BOT_PERMISSIONS,
			'is_admin'                             => is_admin(),
			'ets_restrictcontentpro_discord_nonce' => wp_create_nonce( 'ets-restrictcontentpro-discord-ajax-nonce' ),
		);

		wp_localize_script( $this->plugin_name, 'etsRestrictcontentParams', $script_params );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu_for_restricted_content_discord() {
		add_submenu_page( 'rcp-members', esc_html__( 'Discord Settings', 'restrictcontentpro-discord-addon' ), esc_html__( 'Discord Settings', '' ), 'manage_options', 'rcp-discord', array( $this, 'ets_restrict_content_discord_setting_page' ) );
	}

	/**
	 * Add plugin admin view.
	 */
	public function ets_restrict_content_discord_setting_page() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		wp_enqueue_style( $this->plugin_name . '-select2' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name . 'restrictcontent_tabs_css' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name . 'res_font_awesome_css' );
		wp_enqueue_script( $this->plugin_name . 'restrictcontent_tabs_js' );
		wp_enqueue_script( $this->plugin_name . '-select2' );
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/restrictcontentpro-discord-addon-admin-display.php';
	}

	/**
	 * Save plugin general settings.
	 *
	 * @since    1.0.0
	 */
	public function ets_restrictcontentpro_discord_general_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_restrictcontentpro_discord_client_id = isset( $_POST['ets_restrictcontentpro_discord_client_id'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_client_id'] ) ) : '';

		$discord_client_secret = isset( $_POST['ets_restrictcontentpro_discord_client_secret'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_client_secret'] ) ) : '';

		$discord_bot_token = isset( $_POST['ets_restrictcontentpro_discord_bot_token'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_bot_token'] ) ) : '';

		$ets_restrictcontentpro_discord_redirect_url       = isset( $_POST['ets_restrictcontentpro_discord_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_redirect_url'] ) ) : '';
		$ets_restrictcontentpro_discord_admin_redirect_url = isset( $_POST['ets_restrictcontentpro_discord_admin_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_admin_redirect_url'] ) ) : '';

		$ets_restrictcontentpro_discord_server_id = isset( $_POST['ets_restrictcontentpro_discord_server_id'] ) ? sanitize_text_field( trim( $_POST['ets_restrictcontentpro_discord_server_id'] ) ) : '';

		if ( isset( $_POST['submit'] ) && ! isset( $_POST['restrictcontentpro_discord_general_settings'] ) ) {
			if ( isset( $_POST['ets_discord_save_settings'] ) && wp_verify_nonce( $_POST['ets_discord_save_settings'], 'save_discord_settings' ) ) {
				if ( $ets_restrictcontentpro_discord_client_id ) {
					update_option( 'ets_restrictcontentpro_discord_client_id', $ets_restrictcontentpro_discord_client_id );
				}

				if ( $discord_client_secret ) {
					update_option( 'ets_restrictcontentpro_discord_client_secret', $discord_client_secret );
				}

				if ( $discord_bot_token ) {
					update_option( 'ets_restrictcontentpro_discord_bot_token', $discord_bot_token );
				}

				if ( $ets_restrictcontentpro_discord_redirect_url ) {
					// add a query string param `via` GH #185.
					$ets_restrictcontentpro_discord_redirect_url = ets_restrictcontentpro_get_formated_discord_redirect_url( $ets_restrictcontentpro_discord_redirect_url );
					update_option( 'ets_restrictcontentpro_discord_redirect_url', $ets_restrictcontentpro_discord_redirect_url );
				}
				if ( $ets_restrictcontentpro_discord_admin_redirect_url ) {
					update_option( 'ets_restrictcontentpro_discord_admin_redirect_url', $ets_restrictcontentpro_discord_admin_redirect_url );
				}
				if ( $ets_restrictcontentpro_discord_server_id ) {
					update_option( 'ets_restrictcontentpro_discord_server_id', $ets_restrictcontentpro_discord_server_id );
				}
				ets_restrictcontentpro_discord_update_bot_name_option();
				$message = esc_html__( 'Your settings are saved successfully.', 'restrictcontentpro-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					// This will delete Stale DM channels.
					delete_metadata( 'user', 0, '_ets_restrictcontentpro_discord_dm_channel', '', true );
					$pre_location = sanitize_text_field( $_POST['current_url'] ) . '&save_settings_msg=' . $message . '#res_general_setting';
					wp_safe_redirect( $pre_location );
				}
			}
		}
	}
	/**
	 * Method to catch the admin BOT connect action
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_connect_bot() {
		if ( isset( $_GET['action'] ) && 'rescp-discord-connectToBot' === $_GET['action'] ) {
			if ( ! current_user_can( 'administrator' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
			}
			$params                    = array(
				'client_id'            => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ),
				'permissions'          => RESTRICT_CONTENT_DISCORD_BOT_PERMISSIONS,
				'response_type'        => 'code',
				'scope'                => 'bot',
				'guild_id'             => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) ),
				'disable_guild_select' => 'true',
				'redirect_uri'         => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_admin_redirect_url' ) ) ),
			);
			$discord_authorise_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'oauth2/authorize?' . http_build_query( $params );

			wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
			exit;
		}
	}
	/**
	 * Save plugin general settings.
	 *
	 * @since    1.0.0
	 */
	public function ets_restrictcontentpro_discord_role_mapping() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$ets_discord_roles = isset( $_POST['ets_restrictcontentpro_discord_role_mapping'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_role_mapping'] ) ) : '';

		$ets_restrictcontentpro_discord_default_role_id = isset( $_POST['resdefaultRole'] ) ? sanitize_textarea_field( trim( $_POST['resdefaultRole'] ) ) : '';

		$allow_none_member = isset( $_POST['res_allow_none_member'] ) ? sanitize_textarea_field( trim( $_POST['res_allow_none_member'] ) ) : '';

		$ets_discord_roles   = stripslashes( $ets_discord_roles );
		$save_mapping_status = update_option( 'ets_restrictcontentpro_discord_role_mapping', $ets_discord_roles );
		if ( isset( $_POST['ets_restrictcontentpro_discord_role_mappings_nonce'] ) && wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_role_mappings_nonce'], 'discord_role_mappings_nonce' ) ) {
			if ( ( $save_mapping_status || isset( $_POST['ets_restrictcontentpro_discord_role_mapping'] ) ) && ! isset( $_POST['flush'] ) ) {
				if ( $ets_restrictcontentpro_discord_default_role_id ) {
					update_option( 'ets_restrictcontentpro_discord_default_role_id', $ets_restrictcontentpro_discord_default_role_id );
				}

				if ( $allow_none_member ) {
					update_option( 'ets_restrictcontentpro_allow_none_member', $allow_none_member );
				}

				$message = esc_html__( 'Your mappings are saved successfully.', 'restrictcontentpro-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					$pre_location = sanitize_text_field( wp_unslash( $_POST['current_url'] ) ) . '&save_settings_msg=' . $message . '#res_role_mapping';
					wp_safe_redirect( $pre_location );
				}
			}
			if ( isset( $_POST['flush'] ) ) {
				delete_option( 'ets_restrictcontentpro_discord_role_mapping' );
				delete_option( 'ets_restrictcontentpro_discord_default_role_id' );
				delete_option( 'ets_restrictcontentpro_allow_none_member' );
				$message = esc_html__( 'Your settings flushed successfully.', 'restrictcontentpro-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					$pre_location = sanitize_text_field( wp_unslash( $_POST['current_url'] ) ) . '&save_settings_msg=' . $message . '#res_role_mapping';
					wp_safe_redirect( $pre_location );
				}
			}
		}
	}

	/**
	 * Save Advance settings.
	 *
	 * @since    1.0.0
	 */
	public function ets_restrictcontentpro_discord_advance_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$set_job_cnrc = isset( $_POST['set_job_cnrc'] ) ? sanitize_textarea_field( trim( $_POST['set_job_cnrc'] ) ) : '';

		$set_job_q_batch_size = isset( $_POST['set_job_q_batch_size'] ) ? sanitize_textarea_field( trim( $_POST['set_job_q_batch_size'] ) ) : '';

		$retry_api_count = isset( $_POST['ets_restrictcontentpro_retry_api_count'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_retry_api_count'] ) ) : '';

		$ets_restrictcontentpro_discord_send_expiration_warning_dm = isset( $_POST['ets_restrictcontentpro_discord_send_expiration_warning_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_send_expiration_warning_dm'] ) ) : false;

		$ets_restrictcontentpro_discord_expiration_warning_message = isset( $_POST['ets_restrictcontentpro_discord_expiration_warning_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_expiration_warning_message'] ) ) : '';

		$ets_restrictcontentpro_discord_send_membership_expired_dm = isset( $_POST['ets_restrictcontentpro_discord_send_membership_expired_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_send_membership_expired_dm'] ) ) : false;

		$ets_restrictcontentpro_discord_expiration_expired_message = isset( $_POST['ets_restrictcontentpro_discord_expiration_expired_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_expiration_expired_message'] ) ) : '';

		$ets_restrictcontentpro_discord_send_welcome_dm = isset( $_POST['ets_restrictcontentpro_discord_send_welcome_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_send_welcome_dm'] ) ) : false;

		$ets_restrictcontentpro_discord_welcome_message = isset( $_POST['ets_restrictcontentpro_discord_welcome_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_welcome_message'] ) ) : '';

		$ets_restrictcontentpro_discord_send_membership_cancel_dm = isset( $_POST['ets_restrictcontentpro_discord_send_membership_cancel_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_send_membership_cancel_dm'] ) ) : '';

		$ets_restrictcontentpro_discord_cancel_message = isset( $_POST['ets_restrictcontentpro_discord_cancel_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_cancel_message'] ) ) : '';

		if ( isset( $_POST['adv_submit'] ) ) {
			if ( isset( $_POST['ets_discord_save_adv_settings'] ) && wp_verify_nonce( $_POST['ets_discord_save_adv_settings'], 'save_discord_adv_settings' ) ) {
				if ( isset( $_POST['upon_failed_payment'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_payment_failed', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_payment_failed', false );
				}

				if ( isset( $_POST['log_api_res'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_log_api_response', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_log_api_response', false );
				}

				if ( isset( $_POST['retry_failed_api'] ) ) {
					update_option( 'ets_restrictcontentpro_retry_failed_api', true );
				} else {
					update_option( 'ets_restrictcontentpro_retry_failed_api', false );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_send_welcome_dm'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_send_welcome_dm', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_send_welcome_dm', false );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_send_expiration_warning_dm'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_send_expiration_warning_dm', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_send_expiration_warning_dm', false );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_welcome_message'] ) && $_POST['ets_restrictcontentpro_discord_welcome_message'] != '' ) {
					update_option( 'ets_restrictcontentpro_discord_welcome_message', $ets_restrictcontentpro_discord_welcome_message );
				} else {
					update_option( 'ets_restrictcontentpro_discord_expiration_warning_message', 'Your membership is expiring' );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_expiration_warning_message'] ) && $_POST['ets_restrictcontentpro_discord_expiration_warning_message'] != '' ) {
					update_option( 'ets_restrictcontentpro_discord_expiration_warning_message', $ets_restrictcontentpro_discord_expiration_warning_message );
				} else {
					update_option( 'ets_restrictcontentpro_discord_expiration_warning_message', 'Your membership is expiring' );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_expiration_expired_message'] ) && $_POST['ets_restrictcontentpro_discord_expiration_expired_message'] != '' ) {
					update_option( 'ets_restrictcontentpro_discord_expiration_expired_message', $ets_restrictcontentpro_discord_expiration_expired_message );
				} else {
					update_option( 'ets_restrictcontentpro_discord_expiration_expired_message', 'Your membership is expired' );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_send_membership_expired_dm'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm', false );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_send_membership_cancel_dm'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm', true );
				} else {
					update_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm', false );
				}

				if ( isset( $_POST['ets_restrictcontentpro_discord_cancel_message'] ) && $_POST['ets_restrictcontentpro_discord_cancel_message'] != '' ) {
					update_option( 'ets_restrictcontentpro_discord_cancel_message', $ets_restrictcontentpro_discord_cancel_message );
				} else {
					update_option( 'ets_restrictcontentpro_discord_cancel_message', 'Your membership is cancled' );
				}

				if ( isset( $_POST['set_job_cnrc'] ) ) {
					if ( $set_job_cnrc < 1 ) {
						update_option( 'ets_restrictcontentpro_discord_job_queue_concurrency', 1 );
					} else {
						update_option( 'ets_restrictcontentpro_discord_job_queue_concurrency', $set_job_cnrc );
					}
				}

				if ( isset( $_POST['set_job_q_batch_size'] ) ) {
					if ( $set_job_q_batch_size < 1 ) {
						update_option( 'ets_restrictcontentpro_discord_job_queue_batch_size', 1 );
					} else {
						update_option( 'ets_restrictcontentpro_discord_job_queue_batch_size', $set_job_q_batch_size );
					}
				}

				if ( isset( $_POST['ets_restrictcontentpro_retry_api_count'] ) ) {
					if ( $retry_api_count < 1 ) {
						update_option( 'ets_restrictcontentpro_retry_api_count', 1 );
					} else {
						update_option( 'ets_restrictcontentpro_retry_api_count', $retry_api_count );
					}
				}
				$message = esc_html__( 'Your settings are saved successfully.', 'restrictcontentpro-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					$pre_location = sanitize_text_field( wp_unslash( $_POST['current_url'] ) ) . '&save_settings_msg=' . $message . '#res_advance';
					wp_safe_redirect( $pre_location );
				}
			}
		}
	}

	/**
	 * Save appearance settings.
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_save_appearance_settings() {
		if ( ! current_user_can( 'administrator' ) || ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_appearance_settings_nonce'], 'restrictcontentpro_discord_appearance_settings_nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_restrictcontentpro_discord_connect_button_bg_color    = isset( $_POST['ets_restrictcontentpro_discord_connect_button_bg_color'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_connect_button_bg_color'] ) ) : '';
		$ets_restrictcontentpro_discord_disconnect_button_bg_color = isset( $_POST['ets_restrictcontentpro_discord_disconnect_button_bg_color'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_disconnect_button_bg_color'] ) ) : '';
		$ets_restrictcontentpro_discord_loggedin_button_text       = isset( $_POST['ets_restrictcontentpro_discord_loggedin_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_loggedin_button_text'] ) ) : '';
		$ets_restrictcontentpro_discord_non_login_button_text      = isset( $_POST['ets_restrictcontentpro_discord_non_login_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_non_login_button_text'] ) ) : '';
		$ets_restrictcontentpro_discord_disconnect_button_text     = isset( $_POST['ets_restrictcontentpro_discord_disconnect_button_text'] ) ? sanitize_textarea_field( trim( $_POST['ets_restrictcontentpro_discord_disconnect_button_text'] ) ) : '';

		if ( isset( $_POST['ets_restrictcontentpro_discord_appearance_settings_nonce'] ) && wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_appearance_settings_nonce'], 'restrictcontentpro_discord_appearance_settings_nonce' ) ) {
			if ( isset( $_POST['appearance_submit'] ) ) {

				if ( isset( $_POST['ets_restrictcontentpro_discord_connect_button_bg_color'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_connect_button_bg_color', $ets_restrictcontentpro_discord_connect_button_bg_color );
				} else {
					update_option( 'ets_restrictcontentpro_discord_connect_button_bg_color', '' );
				}
				if ( isset( $_POST['ets_restrictcontentpro_discord_disconnect_button_bg_color'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_disconnect_button_bg_color', $ets_restrictcontentpro_discord_disconnect_button_bg_color );
				} else {
					update_option( 'ets_restrictcontentpro_discord_disconnect_button_bg_color', '' );
				}
				if ( isset( $_POST['ets_restrictcontentpro_discord_loggedin_button_text'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_loggedin_button_text', $ets_restrictcontentpro_discord_loggedin_button_text );
				} else {
					update_option( 'ets_restrictcontentpro_discord_loggedin_button_text', '' );
				}
				if ( isset( $_POST['ets_restrictcontentpro_discord_non_login_button_text'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_non_login_button_text', $ets_restrictcontentpro_discord_non_login_button_text );
				} else {
					update_option( 'ets_restrictcontentpro_discord_non_login_button_text', '' );
				}
				if ( isset( $_POST['ets_restrictcontentpro_discord_disconnect_button_text'] ) ) {
					update_option( 'ets_restrictcontentpro_discord_disconnect_button_text', $ets_restrictcontentpro_discord_disconnect_button_text );
				} else {
					update_option( 'ets_restrictcontentpro_discord_disconnect_button_text', '' );
				}

				$message = esc_html__( 'Your settings are saved successfully.', 'restrictcontentpro-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) );
					$pre_location    = $ets_current_url . '&save_settings_msg=' . $message . '#res_appearance';
					wp_safe_redirect( $pre_location );
				}
			}
		}
	}

	/**
	 *
	 */
	public function ets_restrictcontentpro_discord_update_redirect_url() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security.
		if ( ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_nonce'], 'ets-restrictcontentpro-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$page_id = sanitize_text_field( $_POST['ets_restrictcontentpro_page_id'] );
		if ( isset( $page_id ) ) {
			$formated_discord_redirect_url = ets_restrictcontentpro_get_formated_discord_redirect_url( $page_id );
			update_option( 'ets_restrictcontentpro_discord_redirect_page_id', $page_id );
			update_option( 'ets_restrictcontentpro_discord_redirect_url', $formated_discord_redirect_url );
			$res = array(
				'formated_discord_redirect_url' => $formated_discord_redirect_url,
			);
			wp_send_json( $res );
		}
		exit();
	}

	/**
	 * Clear previous logs history
	 *
	 * @param None
	 * @return None
	 */
	public function ets_restrictcontentpro_discord_clear_logs() {
		if ( ! is_user_logged_in() && ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security.
		if ( isset( $_POST['ets_restrictcontentpro_discord_nonce'] ) && ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_nonce'], 'ets-restrictcontentpro-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		try {
			$uuid      = get_option( 'ets_restrictcontentpro_discord_uuid_file_name' );
			$file_name = $uuid . $this::$log_file_name;
			if ( fopen( WP_CONTENT_DIR . '/' . $file_name, 'w' ) ) {
				$myfile = fopen( WP_CONTENT_DIR . '/' . $file_name, 'w' );
				$txt    = current_time( 'mysql' ) . " => Clear logs Successfully\n";
				fwrite( $myfile, $txt );
				fclose( $myfile );
			} else {
				throw new Exception( 'Could not open the file!' );
			}
		} catch ( Exception $e ) {
			return wp_send_json(
				array(
					'error' => array(
						'msg'  => $e->getMessage(),
						'code' => $e->getCode(),
					),
				)
			);
		}
	}

	/**
	 * Send mail to support form current user
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_send_support_mail() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		if ( isset( $_POST['save'] ) ) {
			// Check for nonce security
			if ( ! wp_verify_nonce( $_POST['ets_discord_send_support_mail'], 'send_support_mail' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
			}
			$etsUserName  = isset( $_POST['ets_user_name'] ) ? sanitize_text_field( trim( $_POST['ets_user_name'] ) ) : '';
			$etsUserEmail = isset( $_POST['ets_user_email'] ) ? sanitize_text_field( trim( $_POST['ets_user_email'] ) ) : '';
			$message      = isset( $_POST['ets_support_msg'] ) ? sanitize_text_field( trim( $_POST['ets_support_msg'] ) ) : '';
			$sub          = isset( $_POST['ets_support_subject'] ) ? sanitize_text_field( trim( $_POST['ets_support_subject'] ) ) : '';

			if ( $etsUserName && $etsUserEmail && $message && $sub ) {

				$subject   = $sub;
				$to        = array( 'contact@expresstechsoftwares.com', 'vinod.tiwari@expresstechsoftwares.com' );
				$content   = 'Name: ' . $etsUserName . '<br>';
				$content  .= 'Contact Email: ' . $etsUserEmail . '<br>';
				$content  .= 'Message: ' . $message;
				$headers   = array();
				$blogemail = get_bloginfo( 'admin_email' );
				$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $blogemail . '>' . "\r\n";
				$mail      = wp_mail( $to, $subject, $content, $headers );

				if ( $mail ) {
					$message = esc_html__( 'Your request have been successfully submitted!', 'restrictcontentpro-discord-addon' );
					if ( isset( $_POST['current_url'] ) ) {
						$pre_location = sanitize_text_field( wp_unslash( $_POST['current_url'] ) ) . '&save_settings_msg=' . $message . '#res_support';
						wp_safe_redirect( $pre_location );
					}
				} else {
					$message = esc_html__( 'Failed to send email!', 'restrictcontentpro-discord-addon' );
					if ( isset( $_POST['current_url'] ) ) {
						$pre_location = sanitize_text_field( wp_unslash( $_POST['current_url'] ) ) . '&save_settings_msg=' . $message . '#res_support';
						wp_safe_redirect( $pre_location );
					}
				}
			}
		}
	}

	/**
	 * Fetch all roles from discord server
	 *
	 * @return OBJECT REST API response
	 */
	public function ets_restrictcontentpro_load_discord_roles() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security.
		if ( ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_nonce'], 'ets-restrictcontentpro-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$user_id = get_current_user_id();

		$server_id         = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		if ( $server_id && $discord_bot_token ) {
			$discod_server_roles_api = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $server_id . '/roles';
			$guild_args              = array(
				'method'  => 'GET',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
			);
			$guild_response          = wp_remote_post( $discod_server_roles_api, $guild_args );

			ets_restrictcontentpro_discord_log_api_response( $user_id, $discod_server_roles_api, $guild_args, $guild_response );

			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );

			if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
				if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
					res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				} else {
					$response_arr['previous_mapping'] = get_option( 'ets_restrictcontentpro_discord_role_mapping' );

					$discord_roles = array();
					foreach ( $response_arr as $key => $value ) {
						$isbot = false;
						if ( is_array( $value ) ) {
							if ( array_key_exists( 'tags', $value ) ) {
								if ( array_key_exists( 'bot_id', $value['tags'] ) ) {
									$isbot = true;
								}
							}
						}
						if ( 'previous_mapping' !== $key && $isbot == false && isset( $value['name'] ) && $value['name'] != '@everyone' ) {
							$discord_roles[ $value['id'] ]       = $value['name'];
							$discord_roles_color[ $value['id'] ] = $value['color'];
						}
					}
					update_option( 'ets_restrictcontentpro_discord_all_roles', wp_json_encode( $discord_roles ) );
					update_option( 'ets_restrictcontentpro_discord_roles_color', serialize( $discord_roles_color ) );
				}
			}
			return wp_send_json( $response_arr );
		}

	}

	/**
	 * Action schedule to schedule a function to run upon restrictcontentpro delete membership
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_as_schdule_job_restrictcontentpro_membership_delete() {
		if ( ! empty( $_POST['rcp_delete_membership'] ) ) {
			$membership_id      = absint( $_POST['membership_id'] );
			$membership         = rcp_get_membership( $membership_id );
			$access_token       = sanitize_text_field( trim( get_user_meta( $membership->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true ) ) );
			$deleted_membership = array();
			if ( ! empty( $membership ) ) {
					$deleted_membership = array(
						'product_id'     => $membership->get_object_id(),
						'membership_uid' => $membership->get_id(),
						'created_at'     => $membership->get_activated_date(),
						'expires_at'     => $membership->get_expiration_date(),
					);
			}
			if ( $deleted_membership && $access_token ) {
				as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_cancelled', array( $membership->get_user_id(), $deleted_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
			}
		}
	}

	/**
	 * Action scheduler method to process complete transaction event.
	 *
	 * @param INT $user_id
	 * @param INT $complete_txn
	 */
	public function ets_restrictcontentpro_discord_as_handler_restrictcontentpro_complete_transaction( $user_id, $complete_txn ) {
		$restrictcontentpro_discord                     = new Restrictcontentpro_Discord_Addon();
		$plugin_public                                  = new Restrictcontentpro_Discord_Addon_Public( $restrictcontentpro_discord->get_plugin_name(), $restrictcontentpro_discord->get_version() );
		$ets_restrictcontentpro_discord_role_mapping    = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$default_role                                   = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
		$previous_default_role                          = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
		$ets_restrictcontentpro_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_welcome_dm' ) ) );

		if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $complete_txn['product_id'], $ets_restrictcontentpro_discord_role_mapping ) ) {
			$mapped_role_id = sanitize_text_field( trim( $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $complete_txn['product_id'] ] ) );
			if ( $mapped_role_id ) {
				$plugin_public->put_discord_role_api( $user_id, $mapped_role_id, true );
				$assigned_role = array(
					'role_id'    => $mapped_role_id,
					'product_id' => $complete_txn['product_id'],
				);
				update_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $complete_txn['product_id'], $assigned_role );
				// Send welcome message.
				if ( true == $ets_restrictcontentpro_discord_send_welcome_dm && $complete_txn ) {
					as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $complete_txn, 'welcome' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
				}
			}
		}

		// Assign role which is saved as default.
		if ( $default_role != 'none' ) {
			if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
				$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, false );
				delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
			}
			$plugin_public->put_discord_role_api( $user_id, $default_role, true );
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
		} elseif ( $default_role == 'none' ) {
			if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
				$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, true );
			}
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
		}
	}

	/**
	 * Method to adjust level mapped and default role of a member.
	 *
	 * @param INT   $user_id
	 * @param ARRAY $expired_membership
	 * @param ARRAY $cancelled_membership
	 * @param BOOL  $is_schedule
	 */
	private function ets_restrictcontentpro_discord_set_member_roles( $user_id, $expired_membership = false, $cancelled_membership = false, $is_schedule = true ) {
		$restrictcontentpro_discord                  = new Restrictcontentpro_Discord_Addon();
		$plugin_public                               = new Restrictcontentpro_Discord_Addon_Public( $restrictcontentpro_discord->get_plugin_name(), $restrictcontentpro_discord->get_version() );
		$allow_none_member                           = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		$default_role                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
		$ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$active_memberships                          = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
		$previous_default_role                       = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
		$ets_restrictcontentpro_discord_send_membership_expired_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm' ) ) );
		$ets_restrictcontentpro_discord_send_membership_cancel_dm  = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm' ) ) );
		$access_token = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', true );
		$user_txn     = null;
		if ( ! empty( $access_token ) ) {
			if ( $expired_membership ) {
				$user_txn = $expired_membership['product_id'];
			}
			if ( $cancelled_membership ) {
				$user_txn = $cancelled_membership['product_id'];
			}

			if ( $user_txn !== null ) {
				$_ets_restrictcontentpro_discord_role_id = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $user_txn, true );
				// delete already assigned role.
				if ( isset( $_ets_restrictcontentpro_discord_role_id ) && $_ets_restrictcontentpro_discord_role_id != '' && $_ets_restrictcontentpro_discord_role_id != 'none' ) {
						$this->restrictcontentpro_delete_discord_role( $user_id, $_ets_restrictcontentpro_discord_role_id['role_id'], $is_schedule );
						delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $user_txn, true );
				}
				delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_expitration_warning_dm_for_' . $user_txn );
			}

			if ( is_array( $active_memberships ) && count( $active_memberships ) != 0 ) {
				// Assign role which is mapped to the mmebership level.
				foreach ( $active_memberships as $active_membership ) {
					if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $active_membership->get_object_id(), $ets_restrictcontentpro_discord_role_mapping ) ) {
						$mapped_role_id = sanitize_text_field( trim( $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $active_membership->get_object_id() ] ) );
						if ( $mapped_role_id && $expired_membership == false && $cancelled_membership == false ) {
							$plugin_public->put_discord_role_api( $user_id, $mapped_role_id, $is_schedule );
							$assigned_role = array(
								'role_id'    => $mapped_role_id,
								'product_id' => $active_membership->get_object_id(),
							);
							update_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_membership->get_object_id(), $assigned_role );
							delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_expitration_warning_dm_for_' . $active_membership->get_object_id() );
						}
					}
				}
			}

			// Assign role which is saved as default.
			if ( $default_role != 'none' && $previous_default_role != $default_role || $expired_membership == false && $cancelled_membership == false ) {
				if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
						$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, $is_schedule );
					delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
				}
				$plugin_public->put_discord_role_api( $user_id, $default_role, $is_schedule );
				update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
			} elseif ( $default_role == 'none' ) {
				if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
					$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, $is_schedule );
				}
				update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
			}

			if ( isset( $user_id ) && $allow_none_member == 'no' && empty( $active_memberships ) ) {
				$plugin_public->restrictcontentpro_delete_member_from_guild( $user_id, false );
			}

			// Send DM about expiry, but only when allow_none_member setting is yes
			if ( $ets_restrictcontentpro_discord_send_membership_expired_dm == true && $expired_membership !== false && $allow_none_member = 'yes' ) {
				as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $expired_membership, 'expired' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
			}

			// Send DM about cancel, but only when allow_none_member setting is yes
			if ( $ets_restrictcontentpro_discord_send_membership_cancel_dm == true && $cancelled_membership !== false && $allow_none_member = 'yes' ) {
				as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $cancelled_membership, 'cancel' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
			}
		}
	}

	/**
	 * Schedule delete discord role for a member
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function restrictcontentpro_delete_discord_role( $user_id, $ets_role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_schedule_delete_role', array( $user_id, $ets_role_id, $is_schedule ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} else {
			$this->ets_restrictcontentpro_discord_as_handler_delete_memberrole( $user_id, $ets_role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler to process delete role of a member.
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_restrictcontentpro_discord_as_handler_delete_memberrole( $user_id, $ets_role_id, $is_schedule = true ) {
		$server_id                               = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
		$_ets_restrictcontentpro_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$discord_bot_token                       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$discord_delete_role_api_url             = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $server_id . '/members/' . $_ets_restrictcontentpro_discord_user_id . '/roles/' . $ets_role_id;
		if ( $_ets_restrictcontentpro_discord_user_id ) {
			$param = array(
				'method'  => 'DELETE',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_request( $discord_delete_role_api_url, $param );
			ets_restrictcontentpro_discord_log_api_response( $user_id, $discord_delete_role_api_url, $param, $response );
			if ( ets_restrictcontentpro_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_restrictcontentpro_discord_as_handler_delete_memberrole' );
				}
			}
			return $response;
		}
	}
	/**
	 * Action schedule to schedule a function to run upon restrictcontentpro membership status changed
	 *
	 * @param STRING  $old_status
	 * @param STRING  $new_status
	 * @param INTEGER $membership_id
	 * @return NONE
	 */
	public function ets_restrictcontentpro_rcp_transition_membership_status( $old_status, $new_status, $membership_id ) {
		$membership     = rcp_get_membership( $membership_id );
		$access_token   = sanitize_text_field( trim( get_user_meta( $membership->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true ) ) );
		$old_membership = array();
		if ( ! empty( $membership ) ) {
				$old_membership = array(
					'product_id'     => $membership->get_object_id(),
					'membership_uid' => $membership->get_id(),
					'created_at'     => $membership->get_activated_date(),
					'expires_at'     => $membership->get_expiration_date(),
				);
		}

		if ( $old_status = 'active' && $new_status == 'expired' && $access_token ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_expiry', array( $membership->get_user_id(), $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} elseif ( $old_status = 'active' && ( $new_status == 'cancelled' || $new_status == 'pending' ) && $access_token && ! empty( $membership->get_activated_date() ) ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_cancelled', array( $membership->get_user_id(), $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} elseif ( $new_status = 'active' && $access_token && ! empty( $membership->get_activated_date() ) ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_complete_transaction', array( $membership->get_user_id(), $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		}
	}

	/**
	 * Action schedule to schedule a function to run upon restrictcontentpro payment status changed
	 *
	 * @param STRING  $new_status
	 * @param INTEGER $payment_id
	 * @return NONE
	 */
	public function ets_restrictcontentpro_rcp_update_payment_status( $new_status, $payment_id ) {
		global $rcp_payments_db;
		$payment        = $rcp_payments_db->get_payment( $payment_id );
		$membership     = rcp_get_membership( $payment->membership_id );
		$access_token   = sanitize_text_field( trim( get_user_meta( $membership->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true ) ) );
		$old_membership = array();
		if ( ! empty( $membership ) ) {
				$old_membership = array(
					'product_id'     => $membership->get_object_id(),
					'membership_uid' => $membership->get_id(),
					'created_at'     => $membership->get_activated_date(),
					'expires_at'     => $membership->get_expiration_date(),
				);
		}

		if ( $new_status == 'complete' && $access_token && ! empty( $membership->get_activated_date() ) ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_complete_transaction', array( $membership->get_user_id(), $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} elseif ( ! empty( $new_status ) && $access_token && ! empty( $membership->get_activated_date() ) ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_cancelled', array( $membership->get_user_id(), $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		}
	}

	/*
	* Action scheduler method to process expired restrictcontentpro members.
	* @param INT $user_id
	* @param INT $expired_membership
	*/
	public function ets_restrictcontentpro_discord_as_handler_restrictcontentpro_expiry( $user_id, $expired_membership ) {
		$this->ets_restrictcontentpro_discord_set_member_roles( $user_id, $expired_membership, false, true );
	}

	/*
	* Action scheduler method to process cancelled restrictcontentpro members.
	* @param INT $user_id
	* @param INT $cancelled_membership
	*/
	public function ets_restrictcontentpro_discord_as_handler_restrictcontentpro_cancelled( $user_id, $cancelled_membership ) {
		$this->ets_restrictcontentpro_discord_set_member_roles( $user_id, false, $cancelled_membership, true );
	}

	/**
	 * Send expiration warning DM to discord members.
	 *
	 * @param INT $reminder_id
	 */

	public function ets_restrictcontentpro_discord_send_expiration_warning_dm() {
		$reminder = new RCP_Reminders();
		if ( $reminder ) {
			$reminder_types = $reminder->get_notice_types();
			foreach ( $reminder_types as $type => $name ) {
				$notices = $reminder->get_notices( $type );
				foreach ( $notices as $notice_id => $notice ) {
					$levels      = ! empty( $notice['levels'] ) && is_array( $notice['levels'] ) ? $notice['levels'] : 'all';
					$memberships = $reminder->get_reminder_subscriptions( $notice['send_period'], $type, $levels );
					if ( is_array( $memberships ) ) {
						foreach ( $memberships as $membership ) {
							if ( $type == 'expiration' && $membership->is_active() ) {
								if ( isset( $membership ) ) {
									$access_token          = get_user_meta( $membership->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true );
									$sub_expire_membership = array(
										'product_id'     => $membership->get_object_id(),
										'membership_uid' => $membership->get_id(),
										'created_at'     => $membership->get_activated_date(),
										'expires_at'     => $membership->get_expiration_date(),
									);
									$sent_time             = rcp_get_membership_meta( $membership->get_id(), '_reminder_sent_' . $notice_id, true );

									if ( ! empty( $access_token ) && empty( $sent_time ) && $sub_expire_membership ) {
										as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $membership->get_user_id(), $sub_expire_membership, 'warning' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Add column into customer table.
	 *
	 * @param INT $user_id
	 */

	public function ets_restrictcontentpro_rcp_members_page_table_column( $columns ) {
		$columns['col_restrictcontentpro_discord']     = esc_html__( 'Discord', 'restrictcontentpro-discord-addon' );
		$columns['col_restrictcontentpro_joined_date'] = esc_html__( 'Joined Date', 'restrictcontentpro-discord-addon' );
		return $columns;
	}

	/**
	 * Add column into customer table.
	 *
	 * @param VARCHAR $value
	 * @param OBJECT  $customers
	 */

	public function ets_restrictcontentpro_rcp_members_page_table_row_discord( $value, $customers ) {
		$access_token = sanitize_text_field( trim( get_user_meta( $customers->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true ) ) );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );
		if ( $access_token ) {
			$discord_username                    = sanitize_text_field( trim( get_user_meta( $customers->get_user_id(), '_ets_restrictcontentpro_discord_username', true ) ) );
			$ets_restrictcontentpro_run_api_btn  = '<p class="' . esc_attr( $customers->get_user_id() ) . ' ets-save-success">Success</p><a class="button button-primary ets-restrictcontentpro-run-api" data-uid="' . esc_attr( $customers->get_user_id() ) . '" href="#">';
			$ets_restrictcontentpro_run_api_btn .= esc_html__( 'Run API', 'restrictcontentpro-discord-addon' );
			$ets_restrictcontentpro_run_api_btn .= '</a><span class="' . esc_attr( $customers->get_user_id() ) . ' spinner"></span>';
			$ets_restrictcontentpro_run_api_btn .= esc_html( $discord_username );
			_e( wp_kses( $ets_restrictcontentpro_run_api_btn, ets_restrictcontentpro_discord_array_allowed_html() ) );

		} else {
			esc_html_e( 'Not Connected', 'restrictcontentpro-discord-addon' );
		}
	}

	/**
	 * Add column into customer table.
	 *
	 * @param VARCHAR $value
	 * @param OBJECT  $customers
	 */

	public function ets_restrictcontentpro_rcp_members_page_table_row_joined_date( $value, $customers ) {
		$access_token = sanitize_text_field( trim( get_user_meta( $customers->get_user_id(), '_ets_restrictcontentpro_discord_access_token', true ) ) );
		if ( $access_token ) {
			echo esc_html( get_user_meta( $customers->get_user_id(), '_ets_restrictcontentpro_discord_join_date', true ) );
		}
	}

	/**
	 * Manage user roles api calls
	 *
	 * @param NONE
	 * @return OBJECT JSON response
	 */
	public function ets_restrictcontentpro_discord_member_table_run_api() {
		if ( ! is_user_logged_in() && current_user_can( 'edit_user' ) ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}

		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_nonce'], 'ets-restrictcontentpro-discord-ajax-nonce' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
		}
		$user_id = sanitize_text_field( trim( $_POST['user_id'] ) );

		// $this->ets_restrictcontentpro_discord_set_member_roles( $user_id, false, false, false );

		$restrictcontentpro_discord                  = new Restrictcontentpro_Discord_Addon();
		$plugin_public                               = new Restrictcontentpro_Discord_Addon_Public( $restrictcontentpro_discord->get_plugin_name(), $restrictcontentpro_discord->get_version() );
		$allow_none_member                           = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		$default_role                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
		$ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$active_memberships                          = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
		$previous_default_role                       = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
		$ets_restrictcontentpro_discord_send_membership_expired_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm' ) ) );
		$ets_restrictcontentpro_discord_send_membership_cancel_dm  = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm' ) ) );
		$access_token = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', true );
		$user_txn     = null;

		/**
		 * IF the member has no active memberships and Allow non-member is set to No
		 *
		 * Remove the member from guild and delete all _ets_restrictcontentpro_discord_role_id_for_* meta_key
		 */
		if ( isset( $user_id ) && empty( $active_memberships ) ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . "usermeta WHERE `meta_key` LIKE '_ets_restrictcontentpro_discord_role_id_for_%' AND user_id =%d", $user_id ) );
			if ( $allow_none_member == 'no' ) {
				$plugin_public->restrictcontentpro_delete_member_from_guild( $user_id, false );
			}
			$event_res = array(
				'status'  => 1,
				'message' => esc_html__( 'success', 'restrictcontentpro-discord-add-on' ),
			);
			return wp_send_json( $event_res );
		}

		if ( is_array( $active_memberships ) && count( $active_memberships ) != 0 ) {
			// Assign role which is mapped to the mmebership level.
			foreach ( $active_memberships as $active_membership ) {
				if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $active_membership->get_object_id(), $ets_restrictcontentpro_discord_role_mapping ) ) {
					$mapped_role_id = sanitize_text_field( trim( $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $active_membership->get_object_id() ] ) );
					if ( $mapped_role_id ) {
						$plugin_public->put_discord_role_api( $user_id, $mapped_role_id );
						$assigned_role = array(
							'role_id'    => $mapped_role_id,
							'product_id' => $active_membership->get_object_id(),
						);
						update_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_membership->get_object_id(), $assigned_role );
						delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_expitration_warning_dm_for_' . $active_membership->get_object_id() );
					}
				} else {

				}
			}
		}

		// Assign role which is saved as default.
		if ( $default_role != 'none' && $previous_default_role != $default_role || $expired_membership == false && $cancelled_membership == false ) {
			if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
					$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, $is_schedule );
				delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', true );
			}
			$plugin_public->put_discord_role_api( $user_id, $default_role, $is_schedule );
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
		} elseif ( $default_role == 'none' ) {
			if ( isset( $previous_default_role ) && $previous_default_role != '' && $previous_default_role != 'none' ) {
				$this->restrictcontentpro_delete_discord_role( $user_id, $previous_default_role, $is_schedule );
			}
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
		}

		// Send DM about expiry, but only when allow_none_member setting is yes
		if ( $ets_restrictcontentpro_discord_send_membership_expired_dm == true && $expired_membership !== false && $allow_none_member = 'yes' ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $expired_membership, 'expired' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		}

		// Send DM about cancel, but only when allow_none_member setting is yes
		if ( $ets_restrictcontentpro_discord_send_membership_cancel_dm == true && $cancelled_membership !== false && $allow_none_member = 'yes' ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $cancelled_membership, 'cancel' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		}

		$event_res = array(
			'status'  => 1,
			'message' => esc_html__( 'success', 'restrictcontentpro-discord-add-on' ),
		);
		return wp_send_json( $event_res );
	}

	// /**
	// * Method to queue all members into cancel job when pmpro level is deleted.
	// *
	// * @param INT $level_id
	// * @return NONE
	// */
	// public function ets_restrictcontentpro_discord_as_schedule_job_membership_level_deleted( $level_id ) {
	// global $wpdb;
	// $members = rcp_get_members_of_subscription( $level_id, 'ID' );
	// $ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
	// foreach ( (array) $members as $key => $member ) {
	// $access_token = sanitize_text_field( trim( get_user_meta( $member->ID, '_ets_restrictcontentpro_discord_access_token', true ) ) );
	// if ( ! empty( $member ) ) {
	// $old_membership = array(
	// 'product_id'     => $member->object_id,
	// 'membership_uid' => $member->ID,
	// 'created_at'     => $member->activated_date,
	// 'expires_at'     => $member->expiration_date,
	// );
	// }

	// if ( $old_membership && $access_token ) {
	// as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_restrictcontentpro_cancelled', array( $member->ID, $old_membership ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
	// }
	// }
	// }

	/**
	 * Manage user roles api calls
	 *
	 * @param NONE
	 * @return OBJECT JSON response
	 */
	public function ets_restrictcontentpro_discord_as_schedule_job_membership_customer_deleted() {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'rcp_delete_customer' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$restrictcontentpro_discord                  = new Restrictcontentpro_Discord_Addon();
		$plugin_public                               = new Restrictcontentpro_Discord_Addon_Public( $restrictcontentpro_discord->get_plugin_name(), $restrictcontentpro_discord->get_version() );
		$deleted                                     = rcp_get_customer( absint( $_GET['customer_id'] ) );
		$user_id                                     = $deleted->get_user_id();
		$access_token                                = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', true ) ) );
		$ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$active_memberships                          = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
		$ets_restrictcontentpro_discord_send_membership_cancel_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm' ) ) );
		$allow_none_member                                        = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		if ( $user_id && $access_token && $allow_none_member == 'no' ) {
			$plugin_public->restrictcontentpro_delete_member_from_guild( $user_id, false );
			delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token' );
		} elseif ( $user_id && $access_token && $allow_none_member == 'yes' ) {
			// Assign role which is mapped to the mmebership level.
			foreach ( $active_memberships as $active_membership ) {
				if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $active_membership->get_object_id(), $ets_restrictcontentpro_discord_role_mapping ) ) {
					$mapped_role_id = sanitize_text_field( trim( $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $active_membership->get_object_id() ] ) );
					if ( $mapped_role_id ) {
						$this->restrictcontentpro_delete_discord_role( $user_id, $mapped_role_id, true );
						delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_membership->get_object_id() );
						$cancelled_membership = array(
							'product_id'     => $active_membership->get_object_id(),
							'membership_uid' => $active_membership->get_id(),
							'created_at'     => $active_membership->get_activated_date(),
							'expires_at'     => $active_membership->get_expiration_date(),
						);
						// Send DM about cancel, but only when allow_none_member setting is yes
						if ( $ets_restrictcontentpro_discord_send_membership_cancel_dm == true && $cancelled_membership ) {
							as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_dm', array( $user_id, $cancelled_membership, 'cancel' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
						}
					}
				}
			}
		}
	}
}
