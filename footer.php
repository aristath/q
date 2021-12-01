<?php
/**
 * Call wp_footer()
 *
 * @package aristath/q
 *
 * @since 1.0
 */

?>
	<?php
	if ( function_exists( 'block_template_part' ) ) {
		block_template_part( 'footer' );
	}
	?>
	</main>
</div>

<?php wp_footer(); ?>
</body>
