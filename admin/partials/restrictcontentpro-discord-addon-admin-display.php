<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Restrictcontentpro_Discord_Addon
 * @subpackage Restrictcontentpro_Discord_Addon/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
if ( isset( $_GET['save_settings_msg'] ) ) {
	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_settings_msg'] ); ?></p>
	</div>
	<?php
}
?>
<h1><?php esc_html_e( 'Restrict Content Pro Discord Add On Settings', 'ets_restrictcontent_discord' ); ?></h1>
		<div id="res-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 1 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="res_settings" ><?php esc_html_e( 'Application details', 'ets_restrictcontent_discord' ); ?><span class="initialtab spinner"></span></button>
				</li>
				<?php if ( ets_restrictcontentpro_discord_check_saved_settings_status() ) : ?>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="level-mapping" ><?php esc_html_e( 'Role Mappings', 'ets_restrictcontent_discord' ); ?></button>
				</li>
				<?php endif; ?>	
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" data-toggle="tab" data-event="ets_advanced"><?php esc_html_e( 'Advanced', 'ets_restrictcontent_discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="appearance" ><?php esc_html_e( 'Appearance', 'ets_restrictcontent_discord' ); ?>	
				</button>
				</li>				
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" data-toggle="tab" data-event="ets_logs"><?php esc_html_e( 'Logs', 'ets_restrictcontent_discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="docs" data-toggle="tab" data-event="ets_docs"><?php esc_html_e( 'Documentation', 'ets_restrictcontent_discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="support" data-toggle="tab" data-event="ets_about_us"><?php esc_html_e( 'Support', 'ets_restrictcontent_discord' ); ?>	
				</button>
				</li>
			</ul>
			<div class="skltbs-panel-group">
				<div id="res_general_setting" class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-settings.php'; ?>
				</div>
				<?php if ( ets_restrictcontentpro_discord_check_saved_settings_status() ) : ?>
				<div id="res_role_mapping" class="skltbs-panel">
					<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-role-level-map.php'; ?>
				</div>
				<?php endif; ?>
				<div id="res_advance" class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-advance.php'; ?>
				</div>
				<div id='res_appearance' class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-appearance.php'; ?>
				</div> 				
				<div id="res_logs" class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-error-log.php'; ?>
				</div>
				<div id="res_docs" class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-documentation.php'; ?>
				</div>
				<div id="res_support" class="skltbs-panel">
				<?php require_once RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/restrictcontentpro-discord-get-support.php'; ?>
				</div>
			</div>
		</div>
