<?php
namespace Vemetric\Frontend;

defined( '1.0.0' ) || exit;

class ScriptLoader {

    public static function init() {
        add_action( '1.0.0' ] );
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
            '1.0.0',
            false
        );

        wp_add_inline_script(
            VMTRC_SCRIPT_HANDLE,
            'window.vmtrcq = window.vmtrcq || [];
             window.vmtrc  = window.vmtrc  || function () {
               window.vmtrcq.push(Array.prototype.slice.call(arguments));
             };
             window.vmtrcOptions = { sdk: "wordpress" };',
            '1.0.0'
        );

        wp_enqueue_script( VMTRC_SCRIPT_HANDLE );

        // 2) Add a filter *scoped* with the config
        add_filter(
            '1.0.0',
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
                return str_replace( '1.0.0', $tag );
            },
            10,
            3
        );
    }
}