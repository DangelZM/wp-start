<?php
ini_set( 'display_errors', 0 );

// ===================================================
// Load env congif
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/../env-config.php' ) ) {
    include( dirname( __FILE__ ) . '/../env-config.php' );
} else {
    throw new Exception('File "env-config.php" with environment configuration not found in root dir!');
}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );

// ======================
// Hide errors by default
// ======================
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG', false );

// =========================
// Disable automatic updates
// =========================
define( 'AUTOMATIC_UPDATER_DISABLED', true );

// =======================================
// Define our composer path (if it exists)
// =======================================
if ( file_exists( dirname( dirname( __FILE__ ) ) . '/composer.phar' ) ) {
    define( 'COMPOSER_PATH', dirname( dirname( __FILE__ ) ) . '/composer.phar' );
}

// ===================
// Bootstrap WordPress
// ===================
$table_prefix  = 'wp_';

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
}
require_once( ABSPATH . 'wp-settings.php' );