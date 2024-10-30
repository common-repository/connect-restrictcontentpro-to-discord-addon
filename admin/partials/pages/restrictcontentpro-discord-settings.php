<?php
$ets_restrictcontentpro_discord_client_id        = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) );
$discord_client_secret                           = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_client_secret' ) ) );
$discord_bot_token                               = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_bot_token' ) ) );
$ets_restrictcontentpro_discord_redirect_url     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_redirect_url' ) ) );
$ets_restrictcontentpro_discord_server_id        = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_server_id' ) ) );
$ets_restrictcontentpro_discord_redirect_page_id = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_redirect_page_id' ) ) );
$ets_restrictcontentpro_discord_connected_bot_name =  sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_connected_bot_name' ) ) );
?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
<input type="hidden" name="action" value="restrictcontentpro_discord_general_settings">
<input type="hidden" name="current_url" value="<?php echo esc_url( ets_restrictcontentpro_discord_get_current_screen_url() ); ?>">   
	<?php wp_nonce_field( 'save_discord_settings', 'ets_discord_save_settings' ); ?>
	<div class="ets-input-group">
		<?php $ets_restrictcontentpro_discord_client_id_value = isset( $ets_restrictcontentpro_discord_client_id ) ? $ets_restrictcontentpro_discord_client_id : ''; ?>
		<label><?php esc_html_e( 'Client ID', 'restrictcontentpro-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_restrictcontentpro_discord_client_id" value="<?php echo esc_attr( $ets_restrictcontentpro_discord_client_id_value ); ?>" required placeholder="<?php esc_html_e( 'Discord Client ID', 'restrictcontentpro-discord-addon' ); ?>">
	</div>
	<div class="ets-input-group">
		<?php $discord_client_secret_value = isset( $discord_client_secret ) ? $discord_client_secret : ''; ?>
		<label><?php esc_html_e( 'Client Secret', 'restrictcontentpro-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_restrictcontentpro_discord_client_secret" value="<?php echo esc_attr( $discord_client_secret_value ); ?>" required placeholder="<?php esc_html_e( 'Discord Client Secret', 'restrictcontentpro-discord-addon' ); ?>">
	</div>
	<div class="ets-input-group">
		<label><?php esc_html_e( 'Redirect URL', 'restrictcontentpro-discord-addon' ); ?> :</label>
		<p class="redirect-url"><b><?php echo esc_url( $ets_restrictcontentpro_discord_redirect_url ); ?></b></p>
		<select class= "ets-input" id="ets_restrictcontentpro_discord_redirect_url" name="ets_restrictcontentpro_discord_redirect_url" style="width: 100%" required>
		<?php _e( ets_restrictcontentpro_discord_pages_list( wp_kses( $ets_restrictcontentpro_discord_redirect_page_id, array( 'option' => array( 'data-page-url' => array() ) ) ) ) ); ?>
		</select>
		<p class="description"><?php esc_html_e( 'Registered discord app redirect url', 'restrictcontentpro-discord-addon' ); ?><span class="spinner"></span></p>
		<p class="description ets-discord-update-message"><?php _e( sprintf( wp_kses( __( 'Redirect URL updated, kindly add/update the same in your discord.com application link <a href="https://discord.com/developers/applications/%s/oauth2/general">https://discord.com/developers</a>', 'restrictcontentpro-discord-addon' ), array( 'a' => array( 'href' => array() ) ) ), $ets_restrictcontentpro_discord_client_id ) ); ?></p>                
	</div>	
	<div class="ets-input-group">
			<label><?php esc_html_e( 'Admin Redirect URL Connect to bot', 'restrictcontentpro-discord-addon' ); ?> :</label>
			<input type="text" class="ets-input" name="ets_restrictcontentpro_discord_admin_redirect_url" value="<?php echo esc_url( get_admin_url( '', 'admin.php' ) ) . '?page=rcp-discord&via=rcp-discord-bot'; ?>" readonly required />
			<p class="description msg-green"><b><?php esc_html_e( 'Copy this URL and paste inside your https://discord.com/developers/applications -> 0Auth2 -> Redirects', 'restrictcontentpro-discord-addon' ); ?></b></p>
		</div>
		<div class="ets-input-group">
			<?php
            if ( isset( $ets_restrictcontentpro_discord_connected_bot_name ) && !empty( $ets_restrictcontentpro_discord_connected_bot_name ) ){
                echo sprintf(__( '<p class="description msg-green">Make sure the Bot <b> %1$s </b> <span class="discord-bot"></span> have the high priority than the roles it has to manage. Open <a target="_blank" href="https://discord.com/channels/%2$s">Discord Server</a></p>', 'restrictcontentpro-discord-addon'), $ets_restrictcontentpro_discord_connected_bot_name, $ets_restrictcontentpro_discord_server_id );
            }
            ?>

		<?php $discord_bot_token_value = isset( $discord_bot_token ) ? $discord_bot_token : ''; ?>
		<label><?php esc_html_e( 'Bot Token', 'restrictcontentpro-discord-addon' ); ?> :</label>
		<input type="password" class="ets-input" name="ets_restrictcontentpro_discord_bot_token" value="<?php echo esc_attr( $discord_bot_token_value ); ?>" required placeholder="<?php esc_html_e( 'Discord Bot Token', 'restrictcontentpro-discord-addon' ); ?>">
	</div>
	<div class="ets-input-group">
		<?php $ets_restrictcontentpro_discord_server_id_value = isset( $ets_restrictcontentpro_discord_server_id ) ? $ets_restrictcontentpro_discord_server_id : ''; ?>
		<label><?php esc_html_e( 'Server Id', 'restrictcontentpro-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_restrictcontentpro_discord_server_id" placeholder="<?php esc_html_e( 'Discord Server Id', 'restrictcontentpro-discord-addon' ); ?>" value="<?php echo esc_attr( $ets_restrictcontentpro_discord_server_id_value ); ?>" required>
	</div>
	<?php if ( empty( $ets_restrictcontentpro_discord_client_id ) || empty( $discord_client_secret ) || empty( $discord_bot_token ) || empty( $ets_restrictcontentpro_discord_redirect_url ) || empty( $ets_restrictcontentpro_discord_server_id ) ) { ?>
		<p class="ets-danger-text description">
		<?php esc_html_e( 'Please save your form', 'restrictcontentpro-discord-addon' ); ?>
		</p>
	<?php } ?>
	<p>
		<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-bg-green">
		<?php esc_html_e( 'Save Settings', 'restrictcontentpro-discord-addon' ); ?>
		</button>
		<?php if ( sanitize_text_field( get_option( 'ets_restrictcontentpro_discord_client_id' ) ) ) : ?>
		<a href="?action=rescp-discord-connectToBot" class="ets-btn btn-connect-to-bot" id="res-connect-discord-bot"><?php esc_html_e( 'Connect your Bot', 'restrictcontentpro-discord-addon' ); ?> <i class='fab fa-discord'></i></a>
		<?php endif; ?>
	</p>
</form>
