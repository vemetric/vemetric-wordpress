<?php
/**
 * Plugin Name: Vemetric
 * Plugin URI:  https://vemetric.com
 * Description: The Official Vemetric Plugin to add lightweight, privacy-first analytics to your WordPress site. Injects the Vemetric script on the front-end and (optionally) enables you to track events in the PHP backend.
 * Version:     0.2.0
 * Author:      Vemetric
 * Author URI:  https://vemetric.com
 * License:     MIT
 * Text Domain: vemetric
 */

defined( 'ABSPATH' ) || exit;

const VMTRC_OPTION_TOKEN   = 'vemetric_project_token';
const VMTRC_OPTION_HOST = 'vemetric_host';
const VMTRC_OPTION_SCRIPT_URL = 'vemetric_script_url';
const VMTRC_OPTION_TRACK_PAGEVIEWS = 'vemetric_track_pageviews';
const VMTRC_OPTION_TRACK_OUTBOUND_LINKS = 'vemetric_track_outbound_links';
const VMTRC_OPTION_TRACK_DATA_ATTRIBUTES = 'vemetric_track_data_attributes';
const VMTRC_OPTION_MASK_PATHS = 'vemetric_mask_paths';

const VMTRC_SCRIPT_HANDLE = 'vmtrc-scr';
const VMTRC_SCRIPT_URL    = 'https://cdn.vemetric.com/main.js';

/* ------------------------------------------------------------
 *  Autoload (Composer PSR-4)
 * ---------------------------------------------------------- */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

/* ------------------------------------------------------------
 *  Instantiate features
 * ---------------------------------------------------------- */
Vemetric\Frontend\ScriptLoader::init();
Vemetric\Admin\SettingsPage::init();

/* ------------------------------------------------------------
 *  Provide a global instance of the Vemetric PHP SDK client  ──  vemetric() → ?Vemetric\Vemetric
 * ---------------------------------------------------------- */
if ( ! function_exists( 'vemetric' ) ) :
    /**
     * Get the singleton Vemetric PHP SDK client or null if not available.
     *
     * Usage:
     *   $vm = vemetric();
     *   $vm?->trackEvent('Foo');
     * 
     * Checkout the Vemetric PHP SDK docs for more information:
     *   https://vemetric.com/docs/sdks/php
     */
    function vemetric(): ?\Vemetric\Vemetric {
        static $instance = null;

        if ( isset( $instance ) ) {
            return $instance;
        }

        // SDK class present?
        if ( ! class_exists( \Vemetric\Vemetric::class ) ) {
            return $instance = null;
        }

        $token = trim( get_option( VMTRC_OPTION_TOKEN ) );
        if ( empty( $token ) ) {
            return $instance = null;
        }

        try {
            $host = trim( get_option( VMTRC_OPTION_HOST ) );

            $options = [ 'token' => $token ];
            if ( ! empty( $host ) ) {
                $options['host'] = $host;
            }

            $instance = new \Vemetric\Vemetric( $options );
        } catch ( \Throwable $e ) {
            error_log( '[Vemetric] SDK init failed: ' . $e->getMessage() );
            $instance = null;
        }

        return $instance;
    }
endif;