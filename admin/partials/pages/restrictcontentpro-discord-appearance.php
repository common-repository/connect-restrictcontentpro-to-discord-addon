<?php
$ets_restrictcontentpro_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_connect_button_bg_color' ) ) );
$ets_restrictcontentpro_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_disconnect_button_bg_color' ) ) );
$ets_restrictcontentpro_discord_loggedin_button_text       = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_loggedin_button_text' ) ) );
$ets_restrictcontentpro_discord_non_login_button_text      = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_non_login_button_text' ) ) );
$ets_restrictcontentpro_discord_disconnect_button_text     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_disconnect_button_text' ) ) );
?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="restrictcontentpro_discord_save_appearance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_restrictcontentpro_discord_get_current_screen_url() ); ?>" />
<?php wp_nonce_field( 'restrictcontentpro_discord_appearance_settings_nonce', 'ets_restrictcontentpro_discord_appearance_settings_nonce' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row"><?php esc_html_e( 'Connect/Login Button background color', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
						<input name="ets_restrictcontentpro_discord_connect_button_bg_color" type="text" id="ets_restrictcontentpro_discord_connect_button_bg_color"  class="wp-color-picker-field" value="
						<?php
						if ( isset( $ets_restrictcontentpro_discord_connect_button_bg_color ) ) {
							echo esc_attr( $ets_restrictcontentpro_discord_connect_button_bg_color );
						} else {
							echo '#7bbc36'; }
						?>
						" data-default-color="#7bbc36">
		</fieldset></td> 
	</tr>
	<tr>        
		<th scope="row"><?php esc_html_e( 'Disconnect Button background color', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_restrictcontentpro_discord_disconnect_button_bg_color" type="text" id="ets_restrictcontentpro_discord_disconnect_button_bg_color" value="
		<?php
		if ( isset( $ets_restrictcontentpro_discord_disconnect_button_bg_color ) ) {
			echo esc_attr( $ets_restrictcontentpro_discord_disconnect_button_bg_color ); }
		?>
		" data-default-color="#ff0000">
		</fieldset></td> 
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Text on the Button for logged-in users', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_loggedin_button_text_value = isset( $ets_restrictcontentpro_discord_loggedin_button_text ) ? $ets_restrictcontentpro_discord_loggedin_button_text : ''; ?>
		<input name="ets_restrictcontentpro_discord_loggedin_button_text" type="text" id="ets_restrictcontentpro_discord_loggedin_button_text" value="<?php echo esc_attr( $ets_restrictcontentpro_discord_loggedin_button_text_value ); ?>">
		</fieldset></td> 
	</tr>         
	<tr>
		<th scope="row"><?php esc_html_e( 'Text on the Button for non-login users', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_non_login_button_text_value = isset( $ets_restrictcontentpro_discord_non_login_button_text ) ? $ets_restrictcontentpro_discord_non_login_button_text : ''; ?>
		<input name="ets_restrictcontentpro_discord_non_login_button_text" type="text" id="ets_restrictcontentpro_discord_non_login_button_text" value="<?php echo esc_attr( $ets_restrictcontentpro_discord_non_login_button_text_value ); ?>">
		</fieldset></td> 
	</tr>	
	<tr>
		<th scope="row"><?php esc_html_e( 'Text on the Disconnect Button', 'restrictcontentpro-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_restrictcontentpro_discord_disconnect_button_text_value = isset( $ets_restrictcontentpro_discord_disconnect_button_text ) ? $ets_restrictcontentpro_discord_disconnect_button_text : ''; ?>
		<input name="ets_restrictcontentpro_discord_disconnect_button_text" type="text" id="ets_restrictcontentpro_discord_disconnect_button_text" value="<?php echo esc_attr( $ets_restrictcontentpro_discord_disconnect_button_text_value ); ?>">
		</fieldset></td> 
	</tr>	
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="appearance_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'restrictcontentpro-discord-addon' ); ?>
	</button>
  </div>
</form>
