<?php
$membership_levels = rcp_get_membership_levels( array( 'status' => 'active' ) );
$user_id           = sanitize_text_field( trim( get_current_user_id() ) );
$default_role      = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_discord_default_role_id' ) ) );
$allow_none_member = sanitize_text_field( trim( get_option( 'ets_restrictcontentpro_allow_none_member' ) ) );
?>
<div class="notice notice-warning ets-notice">
	<p><i class='fas fa-info'></i> <?php esc_html_e( 'Drag and Drop the Discord Roles over to the Restrict Content Pro Levels', 'restrictcontentpro-discord-addon' ); ?></p>
</div>
<div class="notice notice-warning ets-notice">
	<p><i class='fas fa-info'></i> <?php esc_html_e( 'Note: Inactive memberships will not display', 'restrictcontentpro-discord-addon' ); ?></p>
</div>
<div class="row-container">
	<div class="ets-column res-discord-roles-col">
		<h2><?php esc_html_e( 'Discord Roles', 'restrictcontentpro-discord-addon' ); ?></h2>
		<hr>
		<div class="res-discord-roles">
			<span class="spinner"></span>
		</div>
	</div>
	<div class="ets-column">
		<h2><?php esc_html_e( 'Restrict Content Pro Memberships', 'restrictcontentpro-discord-addon' ); ?></h2>
		<hr>
		<div class="restrictcontentpro-levels">
			<?php
			foreach ( array_reverse( $membership_levels ) as $key => $value ) {
				?>
				<div class="makeMeDroppable" data-level_id="<?php echo esc_attr( $value->id ); ?>" ><span><?php echo esc_html( $value->name ); ?></span></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
	<input type="hidden" name="action" value="restrictcontentpro_discord_role_mapping">
	<input type="hidden" name="current_url" value="<?php echo esc_url( ets_restrictcontentpro_discord_get_current_screen_url() ); ?>" />
	<table class="form-table" role="presentation">
	<tbody>
		<tr>
			<th scope="row"><label for="resdefaultRole"><?php esc_html_e( 'Default Role', 'restrictcontentpro-discord-addon' ); ?></label></th>
			<td>
				<?php wp_nonce_field( 'discord_role_mappings_nonce', 'ets_restrictcontentpro_discord_role_mappings_nonce' ); ?>
				<input type="hidden" id="selected_default_role" value="<?php echo esc_attr( $default_role ); ?>">
				<select id="resdefaultRole" name="resdefaultRole">
					<option value="none"><?php esc_html_e( '-None-', 'restrictcontentpro-discord-addon' ); ?></option>
				</select>
			<p class="description"><?php esc_html_e( 'This Role will be assigned to all level members', 'restrictcontentpro-discord-addon' ); ?></p>
			</td>
		</tr>
		<tr>
		<th scope="row"><label><?php esc_html_e( 'Allow non-members', 'restrictcontentpro-discord-addon' ); ?></label></th>
		<td>
			<fieldset>
				<label><input type="radio" name="res_allow_none_member" value="yes"  
				<?php
				if ( $allow_none_member == 'yes' ) {
					echo esc_attr( 'checked="checked"' ); }
				?>
				> <span><?php esc_html_e( 'Yes', 'restrictcontentpro-discord-addon' ); ?></span></label><br>
				<label><input type="radio" name="res_allow_none_member" value="no" 
				<?php
				if ( empty( $allow_none_member ) || $allow_none_member == 'no' ) {
					echo esc_attr( 'checked="checked"' ); }
				?>
				> <span><?php esc_html_e( 'No', 'restrictcontentpro-discord-addon' ); ?></span></label>
				<p class="description"><?php esc_html_e( 'This setting will apply on Cancel and Expiry of Membership' ); ?></p>
			</fieldset>
		</td>
		</tr>
	</tbody>
	</table>
	<br>
	<div class="res-mapping-json">
	<textarea id="res_maaping_json_val" name="ets_restrictcontentpro_discord_role_mapping">
	<?php
	if ( isset( $ets_discord_roles ) ) {
		echo esc_html( $ets_discord_roles );}
	?>
	</textarea>
  </div>
  <div class="bottom-btn">
	<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'restrictcontentpro-discord-addon' ); ?>
	</button>
	<button id="RestrictcontentproRevertMapping" name="flush" class="ets-submit ets-bg-red">
	  <?php esc_html_e( 'Flush Mappings', 'restrictcontentpro-discord-addon' ); ?>
	</button>
  </div>
</form>
