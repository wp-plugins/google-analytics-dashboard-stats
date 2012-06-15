<?php 
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.6
 * Project Name: Google Analytics Dashboard Stats
 */
 
 $options = get_option('gads_options');
//$options['email'],$options['password'],$options['id'],$options['label'],$options['sources'],$options['content'],$options['ga_check'],$options['code'],$options['uninstall'],$options['dashboard']
 
?>
        <?php if ( !empty($_POST) ) { ?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'gads') ?></strong></p></div>
		<?php } ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('Google Analytics Dashboard Stats', 'gads'); ?></h2>
			<form action="" method="post" id="gads-options">
				<h3><?php _e('Settings','gads'); ?></h3>
				<table class="form-table gads-opts">
					<tbody>
					    <tr>
							<th scope="row"><?php _e('Delete Options on Uninstall?', 'gads'); ?></th>
							<td><label><input name="gads_uninstall" id="gads_uninstall" value="true" type="checkbox" <?php if ( $options['uninstall'] == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to delete all saved options on deactivation.', 'gads_uninstall'); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Show Dashboard Widget?', 'gads'); ?></th>
							<td><label><input name="gads_dashboard" id="gads_dashboard" value="true" type="checkbox" <?php if ( $options['dashboard'] == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to show widget on dashboard.', 'gads_dashboard'); ?></label></td>
						</tr>
					</tbody>
				</table>
				<div id="gads_widget_options">
				<h3><?php _e('Google Analytics Account Information','gads'); ?></h3>
				<table class="form-table gads-opts">
					<tbody>
					    <tr>
							<th scope="row"><label for="gads_email"><?php _e('Email', 'gads'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( $options['email'] != '' ) echo $options['email']; ?>" id="gads_email" name="gads_email"/>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gads_password"><?php _e('Password', 'gads'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( $options['password'] != '' ) echo $options['password']; ?>" id="gads_password" name="gads_password"/>
							    <div class="description"><?php _e('This will not be your standard password. You will need to setup an <a href="https://support.google.com/accounts/bin/answer.py?hl=en&answer=185833&topic=1056283&ctx=topic" target="_blank">application specific password</a>.', 'gads_desc'); ?></div>
							</td>
						</tr>
					    <tr>
							<th scope="row"><label for="gads_prop_id"><?php _e('Profile ID', 'gads'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( $options['id'] != '' ) echo $options['id']; ?>" id="gads_prop_id" name="gads_prop_id"/>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gads_prop_label"><?php _e('Profie Label', 'gads'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( $options['label'] != '' ) echo $options['label']; ?>" id="gads_prop_label" name="gads_prop_label"/>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><?php _e('Dashboard Widget Display Options','gads'); ?></h3>
				<table class="form-table gads-opts">
					<tbody>
					    <tr>
							<th scope="row"><?php _e('Show Top Sources?', 'gads'); ?></th>
							<td><label><input name="gads_sources" id="gads_sources" value="true" type="checkbox" <?php if ( $options['sources'] == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to list top sources in widget.', 'gads_sources'); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Show Top Content?', 'gads'); ?></th>
							<td><label><input name="gads_content" id="gads_content" value="true" type="checkbox" <?php if ( $options['content'] == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to list top content in widget.', 'gads_content'); ?></label></td>
						</tr>
					</tbody>
				</table>
				</div>
				<h3><?php _e('Tracking Code','gads'); ?></h3>
				<table class="form-table gads-opts">
					<tbody>
					    <tr>
							<th scope="row"><?php _e('Include Analytics Code on Site?', 'gads'); ?></th>
							<td><label><input name="gads_ga_check" id="gads_ga_check" value="true" type="checkbox" <?php if ( $options['ga_check'] == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to include analytics code on your site.', 'gads_ga_check'); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><label for="gads_code"><?php _e('Analytics Code', 'gads'); ?></label></th>
							<td>
								<textarea id="gads_code" name="gads_code" cols="10" rows="8"><?php if ( $options['code'] != '' ) echo htmlentities($options['code']); ?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<?php wp_nonce_field('gads','_wp_gads_nonce'); ?>
					<?php submit_button( __('Save Changes', 'gads'), 'button-primary', 'submit', false ); ?>
				</p>
			</form>
			
		</div>