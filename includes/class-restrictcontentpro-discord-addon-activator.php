<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/includes
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Restrictcontentpro_Discord_Addon_Activator {

	/**
	 * Set default values when activating the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$redirect_url  = ets_restrictcontentpro_get_formated_discord_redirect_url( sanitize_text_field( trim( esc_url( get_site_url() . '/register/edit-your-profile' ) ) ), false );

		update_option( 'ets_restrictcontentpro_discord_payment_failed', true );
		update_option( 'ets_restrictcontentpro_discord_log_api_response', false );
		update_option( 'ets_restrictcontentpro_retry_failed_api', true );
		update_option( 'ets_restrictcontentpro_discord_job_queue_concurrency', 1 );
		update_option( 'ets_restrictcontentpro_discord_job_queue_batch_size', 7 );
		update_option( 'ets_restrictcontentpro_allow_none_member', 'yes' );
		update_option( 'ets_restrictcontentpro_retry_api_count', '5' );
		update_option( 'ets_restrictcontentpro_discord_send_welcome_dm', true );
		update_option( 'ets_restrictcontentpro_discord_welcome_message', 'Hi [MEMBER_USERNAME] ([MEMBER_EMAIL]), Welcome, Your membership [MEMBERSHIP_LEVEL] is starting from [MEMBERSHIP_STARTDATE] at [SITE_URL] the last date of your membership is [MEMBERSHIP_ENDDATE] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_restrictcontentpro_discord_send_expiration_warning_dm', true );
		update_option( 'ets_restrictcontentpro_discord_expiration_warning_message', 'Hi [MEMBER_USERNAME] ([MEMBER_EMAIL]), Your membership [MEMBERSHIP_LEVEL] is expiring at [MEMBERSHIP_ENDDATE] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm', true );
		update_option( 'ets_restrictcontentpro_discord_expiration_expired_message', 'Hi [MEMBER_USERNAME] ([MEMBER_EMAIL]), Your membership [MEMBERSHIP_LEVEL] is expired at [MEMBERSHIP_ENDDATE] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm', true );
		update_option( 'ets_restrictcontentpro_discord_cancel_message', 'Hi [MEMBER_USERNAME], ([MEMBER_EMAIL]), Your membership [MEMBERSHIP_LEVEL] at [BLOG_NAME] is cancelled, Regards, [SITE_URL]' );
		update_option( 'ets_restrictcontentpro_discord_uuid_file_name', wp_generate_uuid4() );
		update_option( 'ets_restrictcontentpro_discord_connect_button_bg_color', '#7bbc36' );
		update_option( 'ets_restrictcontentpro_discord_disconnect_button_bg_color', '#ff0000' );
		update_option( 'ets_restrictcontentpro_discord_loggedin_button_text', 'Connect To Discord' );
		update_option( 'ets_restrictcontentpro_discord_non_login_button_text', 'Login With Discord' );
		update_option( 'ets_restrictcontentpro_discord_disconnect_button_text', 'Disconnect From Discord' );
		update_option( 'ets_restrictcontentpro_discord_redirect_url', $redirect_url );
	}

}
