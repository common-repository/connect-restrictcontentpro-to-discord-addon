<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/public
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Restrictcontentpro_Discord_Addon_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The single object Restrictcontentpro_Discord_Addon_Public
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var Restrictcontentpro_Discord_Addon_Public
	 */
	private static $instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Get single instance of Restrictcontentpro_Discord_Addon_Public Class.
	 *
	 * @param STRING $plugin_name The plugin name.
	 * @param STRING $version The plugin version.
	 *
	 * @return OBJECT
	 */
	public static function get_restrictcontentpro_discord_public_instance( $plugin_name, $version ) {

		if ( ! self::$instance ) {
			self::$instance = new Restrictcontentpro_Discord_Addon_Public( $plugin_name, $version );

		}
		return self::$instance;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/restrictcontentpro-discord-addon-public' . $min_css . '.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_register_script( $this->plugin_name . 'public_js', plugin_dir_url( __FILE__ ) . 'js/restrictcontentpro-discord-addon-public' . $min_js . '.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                                  => admin_url( 'admin-ajax.php' ),
			'permissions_const'                           => RESTRICT_CONTENT_DISCORD_BOT_PERMISSIONS,
			'ets_restrictcontentpro_discord_public_nonce' => wp_create_nonce( 'ets-restrictcontentpro-discord-public-ajax-nonce' ),
		);

		wp_localize_script( $this->plugin_name . 'public_js', 'etsRestrictcontentpublicParams', $script_params );
	}

	/**
	 * Add discord connection buttons.
	 *
	 * @since    1.0.0
	 */
	public function ets_restrictcontentpro_discord_add_connect_button() {
		if ( ! is_user_logged_in() ) {
			// wp_send_json_error( 'Unauthorized user', 401 );
			// exit();
			return;
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name . 'public_js' );
		$user_id                                     = sanitize_text_field( trim( get_current_user_id() ) );
		$discord_user_id                             = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$discord_username                            = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_username', true ) ) );
		$access_token                                = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', true ) ) );
		$allow_none_member                           = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		$default_role                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
		$ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$all_roles                                   = json_decode( get_option( 'ets_restrictcontentpro_discord_all_roles' ), true );
		$roles_color                                 = unserialize( get_option( 'ets_restrictcontentpro_discord_roles_color' ) );
		$active_memberships                          = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
		$ets_restrictcontentpro_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_connect_button_bg_color' ) ) );
		$ets_restrictcontentpro_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_disconnect_button_bg_color' ) ) );
		$ets_restrictcontentpro_discord_disconnect_button_text     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_disconnect_button_text' ) ) );
		$ets_restrictcontentpro_discord_loggedin_button_text       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_loggedin_button_text' ) ) );

		$mapped_role_names = '';
		if ( $active_memberships && is_array( $all_roles ) ) {
			foreach ( $active_memberships as $active_membership ) {
				if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $active_membership->get_object_id(), $ets_restrictcontentpro_discord_role_mapping ) ) {
					$mapped_role_id = $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $active_membership->get_object_id() ];
					if ( array_key_exists( $mapped_role_id, $all_roles ) ) {
						// array_push( $mapped_role_names, $all_roles[ $mapped_role_id ] );
						$mapped_role_names .= '<span> <i style="background-color:#' . dechex( $roles_color[ $mapped_role_id ] ) . '"></i>' . $all_roles[ $mapped_role_id ] . '</span>';
					}
				}
			}
		}
		$default_role_name = '';
		if ( 'none' !== $default_role && is_array( $all_roles ) && array_key_exists( $default_role, $all_roles ) ) {
			// $default_role_name = $all_roles[ $default_role ];
			$default_role_name = '<span><i style="background-color:#' . dechex( $roles_color[ $default_role ] ) . '"></i> ' . $all_roles[ $default_role ] . '</span>';
		}
		$ets_restrictcontentpro_connecttodiscord_btn = '';
		if ( ets_restrictcontentpro_discord_check_saved_settings_status() ) {
			if ( $access_token ) {
				$discord_user_avatar                          = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_avatar', true ) ) );
				$disconnect_btn_bg_color                      = 'style="background-color:' . $ets_restrictcontentpro_discord_disconnect_button_bg_color . '"';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<div class="ets_restrictcontentpro_connect_btn_wrapper">';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<label class="ets-connection-lbl">' . esc_html__( 'Discord connection', 'restrictcontentpro-discord-addon' ) . '</label>';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<a href="#" class="ets-btn res-btn-disconnect" id="disconnect-discord" data-user-id="' . esc_attr( $user_id ) . '" ' . $disconnect_btn_bg_color . '>' . $ets_restrictcontentpro_discord_disconnect_button_text . Restrictcontentpro_Discord_Addon::get_discord_logo_white() . '</a>';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<p>' . esc_html__( sprintf( 'Connected account: %s', $discord_username ), 'restrictcontentpro-discord-addon' ) . '</p>';
				$ets_restrictcontentpro_connecttodiscord_btn  = ets_restrictcontentpro_discord_get_user_avatar( $discord_user_id, $discord_user_avatar, $ets_restrictcontentpro_connecttodiscord_btn );
				$ets_restrictcontentpro_connecttodiscord_btn  = ets_restrictcontentpro_discord_roles_assigned_message( $mapped_role_names, $default_role_name, $ets_restrictcontentpro_connecttodiscord_btn );
				$ets_restrictcontentpro_connecttodiscord_btn .= '<span class="ets-spinner"></span>';
				$ets_restrictcontentpro_connecttodiscord_btn .= '</div>';
			} elseif ( rcp_user_has_active_membership() && $mapped_role_names || $allow_none_member == 'yes' ) {
				$connect_btn_bg_color                         = 'style="background-color:' . $ets_restrictcontentpro_discord_connect_button_bg_color . '"';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<div class="ets_restrictcontentpro_connect_btn_wrapper">';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<label class="ets-connection-lbl">' . esc_html__( 'Discord connection', 'restrictcontentpro-discord-addon' ) . '</label>';
				$ets_restrictcontentpro_connecttodiscord_btn .= '<a href="?action=restrictcontentpro-discord-login" class="res-btn-connect ets-btn" ' . $connect_btn_bg_color . ' >' . esc_html( $ets_restrictcontentpro_discord_loggedin_button_text ) . Restrictcontentpro_Discord_Addon::get_discord_logo_white() . '</a>';
				// if ( $mapped_role_names ) {
				// $ets_restrictcontentpro_connecttodiscord_btn .= '<p class="ets_assigned_role">';
				// $ets_restrictcontentpro_connecttodiscord_btn .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'restrictcontentpro-discord-addon' );
				// foreach ( $mapped_role_names as $mapped_role_name ) {
				// $ets_restrictcontentpro_connecttodiscord_btn .= esc_html( $mapped_role_name ) . ', ';
				// }
				// }
				// if ( $default_role_name ) {
				// $ets_restrictcontentpro_connecttodiscord_btn .= esc_html( $default_role_name );
				// }
				$ets_restrictcontentpro_connecttodiscord_btn  = ets_restrictcontentpro_discord_roles_assigned_message( $mapped_role_names, $default_role_name, $ets_restrictcontentpro_connecttodiscord_btn );
				$ets_restrictcontentpro_connecttodiscord_btn .= '</div>';
			}
		}
		return $ets_restrictcontentpro_connecttodiscord_btn;
	}

	/**
	 * Show status of Restrict Content Pro connection with Discord user
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_restrictcontentpro_show_discord_button() {
		echo wp_kses( do_shortcode( '[ets_restrictcontentpro_discord]' ), ets_restrictcontentpro_discord_array_allowed_html() );
	}

	/**
	 * For authorization process call discord API
	 *
	 * @param NONE
	 * @return OBJECT REST API response
	 */
	public function ets_restrictcontentpro_discord_discord_api_callback() {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'restrictcontentpro-discord-login' ) {
				$params                    = array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ),
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_redirect_url' ) ) ),
					'response_type' => 'code',
					'scope'         => 'identify email connections guilds guilds.join messages.read',
				);
				$discord_authorise_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'oauth2/authorize?' . http_build_query( $params );

				wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
				exit;
			}

			if ( isset( $_GET['action'] ) && $_GET['action'] == 'rescp-discord-connectToBot' ) {
				$params                    = array(
					'client_id'   => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ),
					'permissions' => RESTRICT_CONTENT_DISCORD_BOT_PERMISSIONS,
					'scope'       => 'bot',
					'guild_id'    => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) ),
				);
				$discord_authorise_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'oauth2/authorize?' . http_build_query( $params );

				wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
				exit;
			}
			if ( isset( $_GET['code'] ) && isset( $_GET['via'] ) && $_GET['via'] == 'res-discord' ) {
				$membership_private_obj = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
				$active_memberships     = array();
				if ( ! empty( $membership_private_obj ) ) {
					foreach ( $membership_private_obj as $memberships ) {
						$membership_arr = array(
							'product_id'     => $memberships->get_object_id(),
							'membership_uid' => $memberships->get_id(),
							'created_at'     => $memberships->get_activated_date(),
							'expires_at'     => $memberships->get_expiration_date(),
						);
						array_push( $active_memberships, $membership_arr );
					}
				}
				$code     = sanitize_text_field( trim( $_GET['code'] ) );
				$response = $this->ets_restrictcontentpro_create_discord_auth_token( $code, $user_id, $active_memberships );
				if ( ! empty( $response ) && ! is_wp_error( $response ) ) {
					$res_body              = json_decode( wp_remote_retrieve_body( $response ), true );
					$discord_exist_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
					if ( is_array( $res_body ) ) {
						if ( array_key_exists( 'access_token', $res_body ) ) {
							$access_token = sanitize_text_field( trim( $res_body['access_token'] ) );
							update_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', $access_token );
							if ( array_key_exists( 'refresh_token', $res_body ) ) {
								$refresh_token = sanitize_text_field( trim( $res_body['refresh_token'] ) );
								update_user_meta( $user_id, '_ets_restrictcontentpro_discord_refresh_token', $refresh_token );
							}
							if ( array_key_exists( 'expires_in', $res_body ) ) {
								$expires_in = $res_body['expires_in'];
								$date       = new DateTime();
								$date->add( DateInterval::createFromDateString( '' . $expires_in . ' seconds' ) );
								$token_expiry_time = $date->getTimestamp();
								update_user_meta( $user_id, '_ets_restrictcontentpro_discord_expires_in', $token_expiry_time );
							}
							$user_body = $this->get_discord_current_user( $access_token );

							if ( is_array( $user_body ) && array_key_exists( 'discriminator', $user_body ) ) {
								$discord_user_number           = $user_body['discriminator'];
								$discord_user_name             = $user_body['username'];
								$discord_user_avatar           = $user_body['avatar'];
								$discord_user_name_with_number = $discord_user_name . '#' . $discord_user_number;
								update_user_meta( $user_id, '_ets_restrictcontentpro_discord_username', $discord_user_name_with_number );
								update_user_meta( $user_id, '_ets_restrictcontentpro_discord_avatar', $discord_user_avatar );
							}
							if ( is_array( $user_body ) && array_key_exists( 'id', $user_body ) ) {
								$_ets_restrictcontentpro_discord_user_id = sanitize_text_field( trim( $user_body['id'] ) );
								if ( $discord_exist_user_id == $_ets_restrictcontentpro_discord_user_id ) {
									foreach ( $active_memberships as $active_membership ) {
										$_ets_restrictcontentpro_discord_role_id = get_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_membership->get_object_id(), true );
										if ( ! empty( $_ets_restrictcontentpro_discord_role_id ) && $_ets_restrictcontentpro_discord_role_id['role_id'] != 'none' ) {
											$this->restrictcontentpro_delete_discord_role( $user_id, $_ets_restrictcontentpro_discord_role_id['role_id'] );
										}
									}
								}
								update_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', $_ets_restrictcontentpro_discord_user_id );
								$this->ets_restrictcontentpro_discord_add_member_in_guild( $_ets_restrictcontentpro_discord_user_id, $user_id, $access_token, $active_memberships );
							}
						} // IF access token.
					}
				}
			}
		}
	}

	/**
	 * Create authentication token for discord API
	 *
	 * @param STRING $code
	 * @param INT    $user_id
	 * @param ARRAY  $active_memberships
	 * @return OBJECT API response
	 */
	public function ets_restrictcontentpro_create_discord_auth_token( $code, $user_id, $active_memberships ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}

		// stop users who having the direct URL of discord Oauth.
		// We must check IF NONE members is set to NO and user having no active membership.
		$allow_none_member = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		if ( empty( $active_memberships ) && $allow_none_member == 'no' ) {
			return;
		}
		$response              = '';
		$refresh_token         = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_refresh_token', true ) ) );
		$token_expiry_time     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_expires_in', true ) ) );
		$discord_token_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'oauth2/token';
		if ( $refresh_token ) {
			$date              = new DateTime();
			$current_timestamp = $date->getTimestamp();

			if ( $current_timestamp > $token_expiry_time ) {
				$args     = array(
					'method'  => 'POST',
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
					'body'    => array(
						'client_id'     => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ),
						'client_secret' => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_secret' ) ) ),
						'grant_type'    => 'refresh_token',
						'refresh_token' => $refresh_token,
						'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_redirect_url' ) ) ),
						'scope'         => RESTRICT_CONTENT_DISCORD_OAUTH_SCOPES,
					),
				);
				$response = wp_remote_post( $discord_token_api_url, $args );
				ets_restrictcontentpro_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
				if ( ets_restrictcontentpro_discord_check_api_errors( $response ) ) {
					$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
					res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				}
			}
		} else {
			$args     = array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded',
				),
				'body'    => array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ),
					'client_secret' => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_secret' ) ) ),
					'grant_type'    => 'authorization_code',
					'code'          => $code,
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_redirect_url' ) ) ),
					'scope'         => RESTRICT_CONTENT_DISCORD_OAUTH_SCOPES,
				),
			);
			$response = wp_remote_post( $discord_token_api_url, $args );

			ets_restrictcontentpro_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
			if ( ets_restrictcontentpro_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			}
		}

		return $response;
	}

	/**
	 * Get Discord user details from API
	 *
	 * @param STRING $access_token
	 * @return OBJECT REST API response
	 */
	public function get_discord_current_user( $access_token ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$user_id = get_current_user_id();

		$discord_cuser_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'users/@me';
		$param                 = array(
			'headers' => array(
				'Content-Type'  => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $access_token,
			),
		);
		$user_response         = wp_remote_get( $discord_cuser_api_url, $param );
		ets_restrictcontentpro_discord_log_api_response( $user_id, $discord_cuser_api_url, $param, $user_response );

		$response_arr = json_decode( wp_remote_retrieve_body( $user_response ), true );
		res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
		$user_body = json_decode( wp_remote_retrieve_body( $user_response ), true );
		return $user_body;

	}

	/**
	 * Add new member into discord guild
	 *
	 * @param INT    $_ets_restrictcontentpro_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @param ARRAY  $active_memberships
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_add_member_in_guild( $_ets_restrictcontentpro_discord_user_id, $user_id, $access_token, $active_memberships ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$allow_none_member = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
		if ( ! empty( $active_memberships ) || $allow_none_member == 'yes' ) {
			// It is possible that we may exhaust API rate limit while adding members to guild, so handling off the job to queue.
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_handle_add_member_to_guild', array( $_ets_restrictcontentpro_discord_user_id, $user_id, $access_token, $active_memberships ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		}
	}

	/**
	 * Method to add new members to discord guild.
	 *
	 * @param INT    $_ets_restrictcontentpro_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @param ARRAY  $active_memberships
	 * @return NONE
	 */
	public function ets_restrictcontentpro_discord_as_handler_add_member_to_guild( $_ets_restrictcontentpro_discord_user_id, $user_id, $access_token, $active_memberships ) {
		// Since we using a queue to delay the API call, there may be a condition when a member is delete from DB. so put a check.
		if ( get_userdata( $user_id ) == false ) {
			return;
		}
		$guild_id                                       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
		$discord_bot_token                              = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$default_role                                   = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
		$ets_restrictcontentpro_discord_role_mapping    = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
		$discord_role                                   = '';
		$ets_restrictcontentpro_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_welcome_dm' ) ) );
		$discord_roles                                  = array();

		if ( is_array( $active_memberships ) ) {
			foreach ( $active_memberships as $active_membership ) {
				if ( is_array( $ets_restrictcontentpro_discord_role_mapping ) && array_key_exists( 'level_id_' . $active_membership['product_id'], $ets_restrictcontentpro_discord_role_mapping ) ) {
						$discord_role = sanitize_text_field( trim( $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $active_membership['product_id'] ] ) );
						array_push( $discord_roles, $discord_role );
				}
			}
		}

		$guilds_memeber_api_url = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_restrictcontentpro_discord_user_id;
		$guild_args             = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => wp_json_encode(
				array(
					'access_token' => $access_token,
				)
			),
		);
		$guild_response         = wp_remote_post( $guilds_memeber_api_url, $guild_args );
		ets_restrictcontentpro_discord_log_api_response( $user_id, $guilds_memeber_api_url, $guild_args, $guild_response );
		if ( ets_restrictcontentpro_discord_check_api_errors( $guild_response ) ) {
			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_as_handler_add_member_to_guild' );
		}

		foreach ( $discord_roles as $key => $discord_role ) {
			$assigned_role = array(
				'role_id'    => $discord_role,
				'product_id' => $active_memberships[ $key ]['product_id'],
			);
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_memberships[ $key ]['membership_uid'], $assigned_role );
			if ( $discord_role && $discord_role != 'none' && isset( $user_id ) ) {
				$this->put_discord_role_api( $user_id, $discord_role );
			}
		}

		if ( $default_role && 'none' !== $default_role && isset( $user_id ) ) {
			$this->put_discord_role_api( $user_id, $default_role );
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id', $default_role );
		}
		if ( empty( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_join_date', true ) ) ) {
			update_user_meta( $user_id, '_ets_restrictcontentpro_discord_join_date', current_time( 'Y-m-d H:i:s' ) );
		}

		// Send welcome message.
		if ( true == $ets_restrictcontentpro_discord_send_welcome_dm ) {
			foreach ( $active_memberships as $active_membership ) {
				as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_send_welcome_dm', array( $user_id, $active_membership, 'welcome' ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
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
	public function delete_discord_role( $user_id, $ets_role_id, $is_schedule = true ) {
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

			$guild_id                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_guild_id' ) ) );
			$_ets_restrictcontentpro_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
			$discord_bot_token                       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
			$discord_delete_role_api_url             = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_restrictcontentpro_discord_user_id . '/roles/' . $ets_role_id;
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
	 * API call to change discord user role
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function put_discord_role_api( $user_id, $role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_schedule_member_put_role', array( $user_id, $role_id, $is_schedule ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} else {
			$this->ets_restrictcontentpro_discord_as_handler_put_memberrole( $user_id, $role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler for mmeber change role discord.
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function ets_restrictcontentpro_discord_as_handler_put_memberrole( $user_id, $role_id, $is_schedule ) {
		$access_token                            = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token', true ) ) );
		$guild_id                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
		$_ets_restrictcontentpro_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$discord_bot_token                       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$discord_change_role_api_url             = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_restrictcontentpro_discord_user_id . '/roles/' . $role_id;

		if ( $access_token && $_ets_restrictcontentpro_discord_user_id ) {
			$param = array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_get( $discord_change_role_api_url, $param );
			ets_restrictcontentpro_discord_log_api_response( $user_id, $discord_change_role_api_url, $param, $response );
			if ( ets_restrictcontentpro_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_restrictcontentpro_discord_as_handler_put_memberrole' );
				}
			}
		}
	}

	/**
	 * Disconnect user from discord
	 *
	 * @param NONE
	 * @return OBJECT JSON response
	 */
	public function ets_restrictcontentpro_disconnect_from_discord() {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		// Check for nonce security
		if ( isset( $_POST['ets_restrictcontentpro_discord_public_nonce'] ) && ! wp_verify_nonce( $_POST['ets_restrictcontentpro_discord_public_nonce'], 'ets-restrictcontentpro-discord-public-ajax-nonce' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
		}
		$user_id = sanitize_text_field( trim( $_POST['user_id'] ) );
		if ( $user_id ) {
			$this->restrictcontentpro_delete_member_from_guild( $user_id, false );
			delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token' );
		}
		$event_res = array(
			'status'  => 1,
			'message' => 'Successfully disconnected',
		);
		echo wp_json_encode( $event_res );
		die();
	}

	/**
	 * Schedule delete existing user from guild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 */
	public function restrictcontentpro_delete_member_from_guild( $user_id, $is_schedule = true ) {
		if ( $is_schedule && isset( $user_id ) ) {
			as_schedule_single_action( ets_restrictcontentpro_discord_get_random_timestamp( ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() ), 'ets_restrictcontentpro_discord_as_schedule_delete_member', array( $user_id, $is_schedule ), RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME );
		} else {
			if ( isset( $user_id ) ) {
				$this->ets_restrictcontentpro_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule );
			}
		}
	}

	/**
	 * AS Handling member delete from huild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_restrictcontentpro_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule ) {
		$guild_id                                = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
		$discord_bot_token                       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
		$_ets_restrictcontentpro_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id', true ) ) );
		$active_memberships                      = ets_restrictcontentpro_discord_get_active_memberships( $user_id );
		$guilds_delete_memeber_api_url           = RESTRICT_CONTENT_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_restrictcontentpro_discord_user_id;
		$guild_args                              = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);
		$guild_response                          = wp_remote_post( $guilds_delete_memeber_api_url, $guild_args );
		ets_restrictcontentpro_discord_log_api_response( $user_id, $guilds_delete_memeber_api_url, $guild_args, $guild_response );
		if ( ets_restrictcontentpro_discord_check_api_errors( $guild_response ) ) {
			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			res_write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			if ( $is_schedule ) {
				// this exception should be catch by action scheduler.
				throw new Exception( 'Failed in function ets_restrictcontentpro_discord_as_handler_delete_member_from_guild' );
			}
		}

		/*Delete all usermeta related to discord connection*/
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_user_id' );
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_access_token' );
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_refresh_token' );
		if ( is_array( $active_memberships ) ) {
			foreach ( $active_memberships as $active_membership ) {
				delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_role_id_for_' . $active_membership->get_id() );
			}
		}
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_default_role_id' );
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_username' );
		delete_user_meta( $user_id, '_ets_restrictcontentpro_discord_expires_in' );
	}

	/**
	 * Allow data protocol.
	 *
	 * @param string[] $protocols Array of allowed protocols.
	 *
	 * @return array
	 */
	public function ets_restrictcontentpro_discord_allow_data_protocol( $protocols ) {

		$protocols[] = 'data';
		return $protocols;
	}

}
