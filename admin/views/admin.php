<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   AdrotateExtraSettings
 * @author    Daniele 'Mte90' Scasciafratte <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap">
	<style>
		textarea {
			width: 80%;
			height: 100px;
		}
	</style>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <h4>Plugin by <a href="http.//mte90.net">Mte90</a> - <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=danielemte90@alice.it&item_name=Donation Adrotate Extra Settings">Donate with Paypal</a></h4>

	<form action="options.php" method="post">
		<?php
		settings_fields( 'adrotate-extra-settings' );
		do_settings_sections( 'adrotate-extra-settings' );
		submit_button();
		?>
	</form>

</div>