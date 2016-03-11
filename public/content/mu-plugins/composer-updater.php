<?php
/*
Plugin Name: Composer Updater
Description: Sets up a wp cron to run "composer update"
Version: 0.2
Author: Gilbert Pellegrom
Author URI: http://gilbert.pellegrom.me
*/

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

class ComposerUpdater {

    public function __construct()
    {
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        add_action( 'composer_updater_event', array( $this, 'do_composer_update' ) );

        if ( ! wp_next_scheduled( 'composer_updater_event' ) ) {
            wp_schedule_event( time(), 'twicedaily', 'composer_updater_event' );
        }
    }

    public function admin_notices()
    {
        if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) || ! AUTOMATIC_UPDATER_DISABLED ) {
            $class = 'error';
            $message = __( 'Warning: It looks like <code>AUTOMATIC_UPDATER_DISABLED</code> is not enabled while the <strong>Composer Updater</strong> mu-plugin is enabled. Either enable <code>AUTOMATIC_UPDATER_DISABLED</code> or remove the <strong>Composer Updater</strong> mu-plugin.' );
            echo '<div class="' . $class . '"><p>' . $message . '</p></div>';
        }
    }

    public function do_composer_update()
    {
        if ( ! defined( 'COMPOSER_PATH' ) || ! COMPOSER_PATH ) {
            return;
        }
        if ( ! file_exists( COMPOSER_PATH ) ) {
            return;
        }

        try {
            // Extract composer
            $upload_dir = wp_upload_dir();
            if ( ! file_exists( $upload_dir['basedir'] . '/composer/vendor/autoload.php' ) ) {
                $composerPhar = new Phar( COMPOSER_PATH );
                // php.ini setting phar.readonly must be set to 0
                $composerPhar->extractTo( $upload_dir['basedir'] . '/composer' );
            }

            // Load composer and change working dir so /vendor location is correct
            require_once( $upload_dir['basedir'] . '/composer/vendor/autoload.php' );
            chdir( dirname( COMPOSER_PATH ) );

            // Run composer update
            $input = new ArrayInput( array( 'command' => 'update' ) );
            $application = new Application();
            $application->run( $input );
        }
        catch ( \Exception $e ) {
            echo $e->getMessage();
        }
    }

}
new ComposerUpdater();