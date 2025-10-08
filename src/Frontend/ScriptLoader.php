<?php
namespace Vemetric\Frontend;

defined( 'ABSPATH' ) || exit;

class ScriptLoader {

    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_script' ] );
    }

    public static function enqueue_script() {
        $token = trim( get_option( VMTRC_OPTION_TOKEN ) );
        if ( empty( $token ) ) {
            return;                                 // nothing to output
        }

        $host = trim( get_option( VMTRC_OPTION_HOST ) );
        $script_url = trim( get_option( VMTRC_OPTION_SCRIPT_URL ) );
        if ( empty( $script_url ) ) {
            $script_url = VMTRC_SCRIPT_URL;
        }

        $track_pageviews = (bool) get_option( VMTRC_OPTION_TRACK_PAGEVIEWS, true );
        $track_outbound = (bool) get_option( VMTRC_OPTION_TRACK_OUTBOUND_LINKS, true );
        $track_data_attributes = (bool) get_option( VMTRC_OPTION_TRACK_DATA_ATTRIBUTES, true );
        $mask_paths = trim( get_option( VMTRC_OPTION_MASK_PATHS ) );

        // 1) Register + enqueue Vemetric script
        wp_register_script(
            VMTRC_SCRIPT_HANDLE,
            $script_url,
            [],
            null,
            false
        );

        $options_json = wp_json_encode( self::buildOptions( $token, $host, $track_pageviews, $track_outbound, $track_data_attributes, $mask_paths ), JSON_UNESCAPED_SLASHES );

        wp_add_inline_script(
            VMTRC_SCRIPT_HANDLE,
            'window.vmtrcq = window.vmtrcq || [];
             window.vmtrc  = window.vmtrc  || function () {
               window.vmtrcq.push(Array.prototype.slice.call(arguments));
             };
             window.vmtrcOptions = ' . $options_json . ';',
            'before'
        );

        wp_enqueue_script( VMTRC_SCRIPT_HANDLE );

        // 2) Add a filter *scoped* with the config
        add_filter(
            'script_loader_tag',
            function ( $tag, $handle, $src ) use ( $token, $host, $track_pageviews, $track_outbound, $track_data_attributes, $mask_paths ) {
                if ( $handle !== VMTRC_SCRIPT_HANDLE ) {
                    return $tag; // early-out for other scripts
                }

                $attr = ScriptTagBuilder::buildAttributes(
                    $token,
                    $host,
                    $track_pageviews,
                    $track_outbound,
                    $track_data_attributes,
                    $mask_paths
                );
                return str_replace( 'script src="', 'script defer ' . $attr . ' src="', $tag );
            },
            10,
            3
        );
    }

    private static function buildOptions( $token, $host, $track_pageviews, $track_outbound, $track_data_attributes, $mask_paths ) {
        $options = [
            'sdk' => 'wordpress',
            'token' => $token,
        ];

        if ( !empty( $host ) ) {
            $options['host'] = $host;
        }

        if ( $track_pageviews === false ) {
            $options['trackPageViews'] = false;
        }

        if ( $track_outbound === false ) {
            $options['trackOutboundLinks'] = false;
        }

        if ( $track_data_attributes === false ) {
            $options['trackDataAttributes'] = false;
        }

        if ( !empty( $mask_paths ) ) {
            $maskPaths = explode( ',', $mask_paths );
            $maskPaths = array_map( 'trim', $maskPaths );
            $options['maskPaths'] = $maskPaths;
        }

        return $options;
    }
}