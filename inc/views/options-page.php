<?php 
/**
 * @package MM_STATS_Functions
 * @version 1.5.1
 * Project Name: Google Analytics Dashboard Stats
 */
?>
        <?php if ( !empty($_POST) ) { ?>
			<div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'mm_ga_stats') ?></strong></p></div>
		<?php } ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('Analytics Stats', 'mm_ga_stats'); ?></h2>
			<form action="" method="post" id="mm-ga-stats-options">
				<h3><?php _e('Analytics Account Login','mm_ga_stats'); ?></h3>
				<table class="form-table">
					<tbody>
					    <tr>
							<th scope="row"><label for="mm_ga_stats_email"><?php _e('Email', 'mm_ga_stats'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( get_option('mm_ga_stats_email') != '' ) echo get_option('mm_ga_stats_email'); ?>" id="mm_ga_stats_email" name="mm_ga_stats_email"/>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="mm_ga_stats_password"><?php _e('Password', 'mm_ga_stats'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( get_option('mm_ga_stats_password') != '' ) echo get_option('mm_ga_stats_password'); ?>" id="mm_ga_stats_password" name="mm_ga_stats_password"/>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><?php _e('Property Information','mm_ga_stats'); ?></h3>
				<table class="form-table">
					<tbody>
					    <tr>
							<th scope="row"><label for="mm_ga_stats_prop_id"><?php _e('Property ID', 'mm_ga_stats'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( get_option('mm_ga_stats_prop_id') != '' ) echo get_option('mm_ga_stats_prop_id'); ?>" id="mm_ga_stats_prop_id" name="mm_ga_stats_prop_id"/>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="mm_ga_stats_prop_label"><?php _e('Property Label', 'mm_ga_stats'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php if ( get_option('mm_ga_stats_prop_label') != '' ) echo get_option('mm_ga_stats_prop_label'); ?>" id="mm_ga_stats_prop_label" name="mm_ga_stats_prop_label"/>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><?php _e('Display Options','mm_ga_stats'); ?></h3>
				<table class="form-table">
					<tbody>
					    <tr>
							<th scope="row"><?php _e('Show Top Sources?', 'mm_ga_stats'); ?></th>
							<td><label><input name="mm_ga_stats_sources" id="mm_ga_stats_sources" value="true" type="checkbox" <?php if ( get_option('mm_ga_stats_sources') == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to list top sources in widget.', 'mm_ga_stats_sources'); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Show Top Content?', 'mm_ga_stats'); ?></th>
							<td><label><input name="mm_ga_stats_content" id="mm_ga_stats_content" value="true" type="checkbox" <?php if ( get_option('mm_ga_stats_content') == 'true' ) echo ' checked="checked" '; ?> /> &mdash; <?php _e('Check if you want to list top content in widget.', 'mm_ga_stats_sources'); ?></label></td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<?php wp_nonce_field('mm_ga_stats','_wp_mm_ga_stats_nonce'); ?>
					<?php submit_button( __('Save Changes', 'mm_ga_stats'), 'button-primary', 'submit', false ); ?>
				</p>
			</form>
			
		</div>