<?php
namespace Vemetric\Frontend;

class ScriptTagBuilder {
    /**
     * Builds the script tag attributes based on configuration options.
     *
     * @param string $token The tracking token
     * @param string $host The host URL
     * @param bool $track_pageviews Whether to track pageviews
     * @param bool $track_outbound Whether to track outbound links
     * @param bool $track_data_attributes Whether to track data attributes
     * @param string $mask_paths Comma-separated list of paths to mask
     * @return string The concatenated attributes string
     */
    public static function buildAttributes($token, $host, $track_pageviews, $track_outbound, $track_data_attributes, $mask_paths) {
        $attr  = ' data-token="' . esc_attr( $token ) . '"';

        if ( !empty( $host ) ) {
            $attr .= ' data-host="' . esc_attr( $host ) . '"';
        }

        if ( $track_pageviews === false ) {
            $attr .= ' data-track-page-views="false"';
        }

        // Add outbound flag only when explicitly disabled
        if ( $track_outbound === false ) {
            $attr .= ' data-track-outbound-links="false"';
        }

        if ( $track_data_attributes === false ) {
            $attr .= ' data-track-data-attributes="false"';
        }

        if ( !empty( $mask_paths ) ) {
            $maskPaths = explode( ',', $mask_paths );
            $maskPaths = array_map( 'trim', $maskPaths );
            $maskPaths = '["' . implode( '","', $maskPaths ) . '"]';
            $attr .= ' data-mask-paths="' . esc_attr( $maskPaths ) . '"';
        }

        return $attr;
    }
} 