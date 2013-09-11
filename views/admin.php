<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */
?>
<div class="wrap">

	<div class="icon32" id="icon-tools"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections('rr_options'); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
</div>

<?php 

