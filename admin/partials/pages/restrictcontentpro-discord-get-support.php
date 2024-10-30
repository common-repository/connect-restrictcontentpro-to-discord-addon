<div class="contact-form ">
	<form method="post" action="<?php echo esc_attr( get_site_url() ) . '/wp-admin/admin-post.php'; ?>">
		
		  
	  <div class="ets-container">
		<div class="top-logo-title">
		  <img src="<?php echo RESTRICT_CONTENT_DISCORD_PLUGIN_DIR_URL . 'admin/images/ets-logo.png'; ?>" class="img-fluid company-logo" alt="">
		  <h1><?php esc_html_e( 'ExpressTech Softwares Solutions Pvt. Ltd.', 'ets_restrictcontent_discord' ); ?></h1>
		  <p><?php esc_html_e( 'ExpressTech Software Solution Pvt. Ltd. is the leading Enterprise WordPress development company.', 'ets_restrictcontent_discord' ); ?><br>
		  <?php esc_html_e( 'Contact us for any WordPress Related development projects.', 'ets_restrictcontent_discord' ); ?></p>
		</div>

		<ul style="text-align: left;">
			<li class="mp-icon mp-icon-right-big"><?php esc_html_e( 'If you encounter any issues or errors, please report them on our support forum for Connect Restrict Content to Discord plugin. Our community will be happy to help you troubleshoot and resolve the issue.', 'ets_restrictcontent_discord' ); ?></li>
			<li class="mp-icon mp-icon-right-big">
			<?php
			echo wp_kses(
				'<a target="_blank" href="https://wordpress.org/support/plugin/connect-restrictcontentpro-to-discord-addon/">Support » Plugin: Connect Restrict Content to Discord</a>',
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			);
			?>
 </li>
		</ul>


	  </div>
  </form>
</div>
