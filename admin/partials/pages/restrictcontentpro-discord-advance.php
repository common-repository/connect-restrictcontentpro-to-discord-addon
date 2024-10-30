<?php
$upon_failed_payment  = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_payment_failed' ) ) );
$log_api_res          = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_log_api_response' ) ) );
$retry_failed_api     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_retry_failed_api' ) ) );
$set_job_cnrc         = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_job_queue_concurrency' ) ) );
$set_job_q_batch_size = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_job_queue_batch_size' ) ) );
$retry_api_count      = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_retry_api_count' ) ) );
$ets_restrictcontentpro_discord_send_expiration_warning_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_expiration_warning_dm' ) ) );
$ets_restrictcontentpro_discord_expiration_warning_message = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_expiration_warning_message' ) ) );
$ets_restrictcontentpro_discord_expired_message            = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_expired_message' ) ) );
$ets_restrictcontentpro_discord_send_membership_expired_dm = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_expired_dm' ) ) );
$ets_restrictcontentpro_discord_expiration_expired_message = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_expiration_expired_message' ) ) );
$ets_restrictcontentpro_discord_send_welcome_dm            = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_welcome_dm' ) ) );
$ets_restrictcontentpro_discord_welcome_message            = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_welcome_message' ) ) );
$ets_restrictcontentpro_discord_send_membership_cancel_dm  = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_send_membership_cancel_dm' ) ) );
$ets_restrictcontentpro_discord_cancel_message             = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_cancel_message' ) ) );
?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
<input type="hidden" name="action" value="restrictcontentpro_discord_advance_settings">
<input type="hidden" name="current_url" value="<?php echo esc_url( ets_restrictcontentpro_discord_get_current_screen_url() ); ?>">   
<?php wp_nonce_field( 'save_discord_adv_settings', 'ets_discord_save_adv_settings' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row"><?php esc_html_e( 'Shortcode:', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		[ets_restrictcontentpro_discord]
		<br/>
		<small><?php esc_html_e( 'Use this shortcode [ets_restrictcontentpro_discord] to display connect to discord button on any page.', 'restrictcontentpro-discord-addon' ); ?></small>
		</fieldset></td>
	</tr> 
  <tr>
		<th scope="row"><?php esc_html_e( 'Send welcome message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_restrictcontentpro_discord_send_welcome_dm" type="checkbox" id="ets_restrictcontentpro_discord_send_welcome_dm" 
		<?php
		if ( $ets_restrictcontentpro_discord_send_welcome_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Membership welcome message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_welcome_message_value = isset( $ets_restrictcontentpro_discord_welcome_message ) ? $ets_restrictcontentpro_discord_welcome_message : ''; ?>
		<textarea class="ets_restrictcontentpro_discord_dm_textarea" name="ets_restrictcontentpro_discord_welcome_message" id="ets_restrictcontentpro_discord_welcome_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( stripslashes_deep( $ets_restrictcontentpro_discord_welcome_message_value ) ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBERSHIP_LEVEL], [SITE_URL], [BLOG_NAME], [MEMBERSHIP_ENDDATE], [MEMBERSHIP_STARTDATE]</small>
		</fieldset></td>
	  </tr>

	<tr>
		<th scope="row"><?php esc_html_e( 'Send membership expiration warning message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_restrictcontentpro_discord_send_expiration_warning_dm" type="checkbox" id="ets_restrictcontentpro_discord_send_expiration_warning_dm" 
		<?php
		if ( $ets_restrictcontentpro_discord_send_expiration_warning_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Membership expiration warning message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_expiration_warning_message_value = isset( $ets_restrictcontentpro_discord_expiration_warning_message ) ? $ets_restrictcontentpro_discord_expiration_warning_message : ''; ?>
		<textarea  class="ets_restrictcontentpro_discord_dm_textarea" name="ets_restrictcontentpro_discord_expiration_warning_message" id="ets_restrictcontentpro_discord_expiration_warning_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( stripslashes_deep( $ets_restrictcontentpro_discord_expiration_warning_message_value ) ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBERSHIP_LEVEL], [SITE_URL], [BLOG_NAME], [MEMBERSHIP_ENDDATE], [MEMBERSHIP_STARTDATE]</small>
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send membership expired message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_restrictcontentpro_discord_send_membership_expired_dm" type="checkbox" id="ets_restrictcontentpro_discord_send_membership_expired_dm" 
		<?php
		if ( $ets_restrictcontentpro_discord_send_membership_expired_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Membership expired message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_expiration_expired_message_value = isset( $ets_restrictcontentpro_discord_expiration_expired_message ) ? $ets_restrictcontentpro_discord_expiration_expired_message : ''; ?>
		<textarea  class="ets_restrictcontentpro_discord_dm_textarea" name="ets_restrictcontentpro_discord_expiration_expired_message" id="ets_restrictcontentpro_discord_expiration_expired_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( stripslashes_deep( $ets_restrictcontentpro_discord_expiration_expired_message_value ) ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBERSHIP_LEVEL], [SITE_URL], [BLOG_NAME]</small>
		</fieldset>
  </td>
		</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send membership cancel message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_restrictcontentpro_discord_send_membership_cancel_dm" type="checkbox" id="ets_restrictcontentpro_discord_send_membership_cancel_dm" 
		<?php
		if ( $ets_restrictcontentpro_discord_send_membership_cancel_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
		<tr>
		<th scope="row"><?php esc_html_e( 'Membership cancel message', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_cancel_message_value = isset( $ets_restrictcontentpro_discord_cancel_message ) ? $ets_restrictcontentpro_discord_cancel_message : ''; ?>
		<textarea  class="ets_restrictcontentpro_discord_dm_textarea" name="ets_restrictcontentpro_discord_cancel_message" id="ets_restrictcontentpro_discord_cancel_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( stripslashes_deep( $ets_restrictcontentpro_discord_cancel_message_value ) ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [MEMBER_USERNAME], [MEMBER_EMAIL], [MEMBERSHIP_LEVEL], [SITE_URL], [BLOG_NAME]</small>
		</fieldset>
  </td>
		</tr>
  <tr>
		<th scope="row"><?php esc_html_e( 'Re-assign roles upon payment failure', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="upon_failed_payment" type="checkbox" id="upon_failed_payment" 
		<?php
		if ( $upon_failed_payment == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	  </tr>
		<th scope="row"><?php esc_html_e( 'Retry Failed API calls', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="retry_failed_api" type="checkbox" id="retry_failed_api" 
		<?php
		if ( $retry_failed_api == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'How many times a failed API call should get re-try', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $retry_api_count_value = isset( $retry_api_count ) ? $retry_api_count : 1; ?>
		<input name="ets_restrictcontentpro_retry_api_count" type="number" min="1" id="ets_restrictcontentpro_retry_api_count" value="<?php echo esc_attr( $retry_api_count_value ); ?>">
		</fieldset></td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue concurrency', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $set_job_cnrc_value = isset( $set_job_cnrc ) ? $set_job_cnrc : 1; ?>
		<input name="set_job_cnrc" type="number" min="1" id="set_job_cnrc" value="<?php echo esc_attr( $set_job_cnrc_value ); ?>">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue batch size', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $set_job_q_batch_size_value = isset( $set_job_q_batch_size ) ? $set_job_q_batch_size : 10; ?>
		<input name="set_job_q_batch_size" type="number" min="1" id="set_job_q_batch_size" value="<?php echo esc_attr( $set_job_q_batch_size_value ); ?>">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Log API calls response (For debugging purpose)', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="log_api_res" type="checkbox" id="log_api_res" 
		<?php
		if ( $log_api_res == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="adv_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'restrictcontentpro-discord-addon' ); ?>
	</button>
  </div>
</form>
