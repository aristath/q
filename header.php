<?php
/**
 * Call wp_head().
 *
 * @package aristath/q
 *
 * @since 1.0
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<div class="wp-site-blocks">
	<?php
	if ( function_exists( 'block_template_part' ) ) {
		block_template_part( 'header' );
	}
	?>
	<main class="wp-block-group">
