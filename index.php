<?php
/**
 * Fallback file in case Gutenberg and Full Site Editing are not enabled.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

get_header();

if ( current_user_can( 'activate_plugins' ) ) {
	echo '<div style="max-width:40em;margin:3em auto;padding:4em;background:#000;color:#fff;">';
	esc_html_e( 'This theme requires the Gutenberg plugin installed. Please visit your dashboard and follow the instructions on the notification displayed to enable the plugin and the Full Site Editing experiment.', 'kiss' );
	echo '</div>';
}
get_footer();
