<div class="error-log">
<?php
	$uuid     = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_uuid_file_name' ) ) );
	$filename = $uuid . Restrictcontentpro_Discord_Addon_Admin::$log_file_name;
	$handle   = fopen( WP_CONTENT_DIR . '/' . $filename, 'a+' );
while ( ! feof( $handle ) ) {
	echo esc_html( fgets( $handle ) ) . '<br />';
}
	fclose( $handle );
?>
</div>
<div class="clrbtndiv">
	<div class="form-group">
		<input type="button" class="clrbtn ets-submit ets-bg-red" id="clrbtn" name="clrbtn" value="Clear Logs !">
		<span class="clr-log spinner" ></span>
	</div>
	<div class="form-group">
		<input type="button" class="ets-submit ets-bg-green" value="Refresh" onClick="window.location.reload()">
	</div>
	<div class="form-group">
		<a href="<?php echo esc_url( content_url( '/' ) ) . $filename; ?>" class="ets-submit ets-bg-download" download><?php esc_html_e( 'Download', 'restrictcontentpro-discord-addon' ); ?></a>
	</div>
	<div class="form-group">
			<a target="_blank" href="<?php echo esc_url( get_admin_url( '', 'tools.php' ) ) . '?page=action-scheduler&status=pending&s=restrictcontentpro'; ?>" class="ets-submit ets-restrictcontentpro-bg-scheduled-actions"><?php esc_html_e( 'Scheduled Actions', 'restrictcontentpro-discord-addon' ); ?></a>
	</div>	
</div>
