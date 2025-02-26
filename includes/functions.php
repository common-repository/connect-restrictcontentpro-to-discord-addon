<?php
/*
* common functions file.
*/

/**
 * Get current screen URL
 *
 * @param NONE
 * @return STRING $url
 */
function ets_restrictcontentpro_discord_get_current_screen_url() {
	$parts       = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );

	return $current_uri;
}

/**
 * Get WP Pages list.
 *
 * @param INT $ets_restrictcontentpro_discord_redirect_page_id
 * @return STRING $options
 */
function ets_restrictcontentpro_discord_pages_list( $ets_restrictcontentpro_discord_redirect_page_id ) {

	$args    = array(
		'sort_order'   => 'asc',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	);
	$pages   = get_pages( $args );
	$options = '<option value="">-</option>';
	foreach ( $pages as $page ) {
		$selected = ( esc_attr( $page->ID ) === $ets_restrictcontentpro_discord_redirect_page_id ) ? ' selected="selected"' : '';
		$options .= '<option data-page-url="' . ets_restrictcontentpro_get_formated_discord_redirect_url( $page->ID ) . '" value="' . esc_attr( $page->ID ) . '" ' . $selected . '>' . $page->post_title . '</option>';
	}

	return $options;
}

/**
 * This method parse url and append a query param to it.
 *
 * @param STRING $page_id The Page ID.
 * @param BOOL   $is_page_id Check if it'a page's ID or url string.
 * @return STRING $url Formatted URL.
 */
function ets_restrictcontentpro_get_formated_discord_redirect_url( $page_id, $is_page_id = true ) {
	if ( $is_page_id ) {
		$url = esc_url( get_permalink( $page_id ) );
	} else {
		$url = esc_url( $page_id );
	}

	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=res-discord';
	} else {
		if ( stristr( $url, 'via=res-discord' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=res-discord';
		}
	}
}

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function ets_restrictcontentpro_discord_check_saved_settings_status() {
	$ets_restrictcontentpro_discord_client_id     = get_option( 'ets_restrictcontentpro_discord_client_id' );
	$ets_restrictcontentpro_discord_client_secret = get_option( 'ets_restrictcontentpro_discord_client_secret' );
	$ets_restrictcontentpro_discord_bot_token     = get_option( 'ets_restrictcontentpro_discord_bot_token' );
	$ets_restrictcontentpro_discord_redirect_url  = get_option( 'ets_restrictcontentpro_discord_redirect_url' );
	$ets_restrictcontentpro_discord_server_id     = get_option( 'ets_restrictcontentpro_discord_server_id' );

	if ( $ets_restrictcontentpro_discord_client_id && $ets_restrictcontentpro_discord_client_secret && $ets_restrictcontentpro_discord_bot_token && $ets_restrictcontentpro_discord_redirect_url && $ets_restrictcontentpro_discord_server_id ) {
			$status = true;
	} else {
			$status = false;
	}

		return $status;
}

/**
 * Log API call response
 *
 * @param INT          $user_id
 * @param STRING       $api_url
 * @param ARRAY        $api_args
 * @param ARRAY|OBJECT $api_response
 */
function ets_restrictcontentpro_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = get_option( 'ets_restrictcontentpro_discord_log_api_response' );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );
		res_write_api_response_logs( $log_string, $user_id );
	}
}

/**
 * Add API error logs into log file
 *
 * @param array  $response_arr
 * @param array  $backtrace_arr
 * @param string $error_type
 * @return None
 */
function res_write_api_response_logs( $response_arr, $user_id, $backtrace_arr = array() ) {
	$error        = current_time( 'mysql' );
	$user_details = '';
	if ( $user_id ) {
		$user_details = '::User Id:' . $user_id;
	}
	$log_api_response = get_option( 'ets_restrictcontentpro_discord_log_api_response' );
	$uuid             = get_option( 'ets_restrictcontentpro_discord_uuid_file_name' );
	$log_file_name    = $uuid . Restrictcontentpro_Discord_Addon_Admin::$log_file_name;
	if ( is_array( $response_arr ) && array_key_exists( 'code', $response_arr ) ) {
		$error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['code'] . ':' . $response_arr['message'];
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	} elseif ( is_array( $response_arr ) && array_key_exists( 'error', $response_arr ) ) {
		$error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['error'];
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	} elseif ( $log_api_response == true ) {
		$error .= json_encode( $response_arr ) . '::' . $user_id;
		file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
	}

}

/**
 * Get restrictcontentpro current level id
 *
 * @param INT $user_id
 * @return ARRAY|NULL $active_memberships
 */
function ets_restrictcontentpro_discord_get_active_memberships( $user_id ) {
	$restrictcontentpro_customer = rcp_get_customer_by_user_id( $user_id );
	if ( $restrictcontentpro_customer ) {
		$restrictcontentpro_user = rcp_get_customer( $restrictcontentpro_customer->get_id() );
	} else {
		$restrictcontentpro_user = '';
	}
	if ( $restrictcontentpro_user ) {
		$active_memberships = $restrictcontentpro_user->get_memberships( array( 'status' => array( 'active' ) ) );
	} else {
		$active_memberships = '';
	}
	if ( $active_memberships ) {
		return $active_memberships;
	} else {
		return null;
	}
}

/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response
 * @param BOOLEAN
 */
function ets_restrictcontentpro_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], ETS_RESTRICT_CONTENT_DISCORD_DONOT_RETRY_THESE_API_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, ETS_RESTRICT_CONTENT_DISCORD_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 */
function ets_restrictcontentpro_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}

/**
 * Get Action data from table `actionscheduler_actions`
 *
 * @param INT $action_id
 */
function ets_restrictcontentpro_discord_as_get_action_data( $action_id ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.hook, aa.status, aa.args, ag.slug AS as_group FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id=ag.group_id WHERE `action_id`=%d AND ag.slug=%s', $action_id, RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result[0];
	} else {
		return false;
	}
}

/**
 * Get the highest available last attempt schedule time
 */

function ets_restrictcontentpro_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', RESTRICT_CONTENT_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get formatted message to send in DM
 *
 * @param INT $user_id
 * * @param ARRAY $membership
 * Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBERSHIP_LEVEL], [SITE_URL], [BLOG_NAME], [MEMBERSHIP_ENDDATE], [MEMBERSHIP_STARTDATE]</small>
 */
function ets_restrictcontentpro_discord_get_formatted_dm( $user_id, $membership, $message ) {
	global $wpdb;
	$user_obj                                    = get_user_by( 'id', $user_id );
	$ets_restrictcontentpro_discord_role_mapping = json_decode( get_option( 'ets_restrictcontentpro_discord_role_mapping' ), true );
	$all_roles                                   = json_decode( get_option( 'ets_restrictcontentpro_discord_all_roles' ), true );
	$mapped_role_id                              = $ets_restrictcontentpro_discord_role_mapping[ 'level_id_' . $membership['product_id'] ];
	$MEMBER_USERNAME                             = sanitize_text_field( $user_obj->user_login );
	$MEMBER_EMAIL                                = sanitize_email( $user_obj->user_email );
	if ( is_array( $all_roles ) && array_key_exists( $mapped_role_id, $all_roles ) ) {
		$MEMBERSHIP_LEVEL = $all_roles[ $mapped_role_id ];
	} else {
		$MEMBERSHIP_LEVEL = '';
	}

	$SITE_URL  = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME = sanitize_text_field( get_bloginfo( 'name' ) );

	if ( ! empty( $membership ) && isset( $membership['created_at'] ) && $membership['created_at'] != '' ) {
		$MEMBERSHIP_STARTDATE = date( 'F jS, Y', strtotime( $membership['created_at'] ) );

	} else {
		$MEMBERSHIP_STARTDATE = '';
	}
	if ( ! empty( $membership ) && isset( $membership['expires_at'] ) && $membership['expires_at'] != '0000-00-00 00:00:00' && $membership['expires_at'] != 'none' ) {
		$MEMBERSHIP_ENDDATE = date( 'F jS, Y', strtotime( $membership['expires_at'] ) );
	} elseif ( ( $membership != null && $membership['expires_at'] == '0000-00-00 00:00:00' ) || $membership['expires_at'] == 'none' ) {
		$MEMBERSHIP_ENDDATE = 'Never';
	} else {
		$MEMBERSHIP_ENDDATE = '';
	}

	$find    = array(
		'[MEMBER_USERNAME]',
		'[MEMBER_EMAIL]',
		'[MEMBERSHIP_LEVEL]',
		'[SITE_URL]',
		'[BLOG_NAME]',
		'[MEMBERSHIP_ENDDATE]',
		'[MEMBERSHIP_STARTDATE]',
	);
	$replace = array(
		$MEMBER_USERNAME,
		$MEMBER_EMAIL,
		$MEMBERSHIP_LEVEL,
		$SITE_URL,
		$BLOG_NAME,
		$MEMBERSHIP_ENDDATE,
		$MEMBERSHIP_STARTDATE,
	);

	return str_replace( $find, $replace, $message );
}

/**
 * The roles assigned message displayed under Connect / Disconnect to discord button.
 *
 * @param STRING $mapped_role_name
 * @param STRING $default_role_name
 * @param STRING $restrictcontent_discord
 *
 * @return STRING Escaped message.
 */
function ets_restrictcontentpro_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord ) {

	if ( $mapped_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'restrictcontentpro-discord-addon' );
		$restrictcontent_discord .= ets_restrictcontentpro_discord_allowed_html( $mapped_role_name );
		if ( $default_role_name ) {
			$restrictcontent_discord .= ets_restrictcontentpro_discord_allowed_html( $default_role_name );

		}

		$restrictcontent_discord .= '</p>';
	} elseif ( $default_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'restrictcontentpro-discord-addon' );
		$restrictcontent_discord .= ets_restrictcontentpro_discord_allowed_html( $default_role_name );

		$restrictcontent_discord .= '</p>';

	}
	return $restrictcontent_discord;
}

/**
 * Allowed html.
 *
 * @param STRING $html_message
 *
 * @return STRING $html_message
 */
function ets_restrictcontentpro_discord_allowed_html( $html_message ) {
	$allowed_html = array(
		'div'    => array(
			'class' => array(),
		),
		'h3'     => array(),
		'p'      => array(),
		'button' => array(
			'id'           => array(),
			'data-user-id' => array(),
			'class'        => array(),
		),
		'span'   => array(),
		'i'      => array(
			'style' => array(),
		),
		'img'    => array(
			'src'   => array(),
			'class' => array(),
		),
	);

	return wp_kses( $html_message, $allowed_html );
}

/**
 * Array of Allowed html to use with wp_kses function.
 *
 * @return ARRAY $html_message
 */
function ets_restrictcontentpro_discord_array_allowed_html() {
	$allowed_html = array(
		'div'    => array(
			'class' => array(),
		),
		'h3'     => array(),
		'p'      => array(
			'class' => array(),
		),
		'button' => array(
			'id'           => array(),
			'data-user-id' => array(),
			'class'        => array(),
		),
		'span'   => array(
			'class' => array(),
		),
		'i'      => array(
			'style' => array(),
		),
		'img'    => array(
			'src'   => array(),
			'class' => array(),
		),
		'a'      => array(
			'class'        => array(),
			'data-uid'     => array(),
			'href'         => array(),
			'style'        => array(),
			'data-user-id' => array(),
		),
		'label'  => array(
			'class' => array(),
		),
	);

	return $allowed_html;
}

/**
 * Get discord user avatar.
 *
 * @param INT    $discord_user_id
 * @param STRING $user_avatar
 * @param STRING $restrictcontent_discord
 *
 * @return STRING
 */
function ets_restrictcontentpro_discord_get_user_avatar( $discord_user_id, $user_avatar, $restrictcontent_discord ) {
	if ( $user_avatar ) {
		$avatar_url               = '<img class="ets-restrictcontentpro-discord-user-avatar" src="https://cdn.discordapp.com/avatars/' . $discord_user_id . '/' . $user_avatar . '.png" />';
		$restrictcontent_discord .= ets_restrictcontentpro_discord_allowed_html( $avatar_url );
	}
	return $restrictcontent_discord;
}

/**
 * Get the bot name using API call
 *
 * @return NONE
 */
function ets_restrictcontentpro_discord_update_bot_name_option() {
	$guild_id          = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
	if ( $guild_id && $discord_bot_token ) {
		$discod_current_user_api = RESTRICT_CONTENT_DISCORD_API_URL . 'users/@me';
		$app_args                = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);

		$app_response = wp_remote_get( $discod_current_user_api, $app_args );
		$response_arr = json_decode( wp_remote_retrieve_body( $app_response ), true );
		if ( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ) {
			update_option( 'ets_restrictcontentpro_discord_connected_bot_name', $response_arr ['username'] );
		} else {
			delete_option( 'ets_restrictcontentpro_discord_connected_bot_name' );
		}
	}
}
