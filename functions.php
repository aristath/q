<?php
/**
 * Functions and hooks for the Q theme.
 *
 * @package aristath/q
 *
 * @since 1.0
 */

// Add global styles.
require_once 'includes/Styles.php';
new \QTheme\Styles();

// Add scripts.
require_once 'includes/Scripts.php';
new \QTheme\Scripts();
