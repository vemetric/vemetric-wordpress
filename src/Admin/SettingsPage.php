<?php
namespace Vemetric\Admin;

defined( 'ABSPATH' ) || exit;

class SettingsPage {

    public static function init() {
        add_action( 'admin_menu',   [ __CLASS__, 'add_menu' ] );
        add_action( 'admin_init',   [ __CLASS__, 'register_setting' ] );
    }

    public static function add_menu() {
        add_options_page(
            'Vemetric',
            'Vemetric',
            'manage_options',
            'vemetric',
            [ __CLASS__, 'render' ]
        );
    }

    public static function register_setting() {
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_TOKEN,
            [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_HOST,
            [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_SCRIPT_URL,
            [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_TRACK_PAGEVIEWS,
            [ 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_TRACK_OUTBOUND_LINKS,
            [ 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_TRACK_DATA_ATTRIBUTES,
            [ 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ]
        );
        register_setting(
            'vemetric_settings',
            VMTRC_OPTION_MASK_PATHS,
            [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ]
        );        
    }

    public static function render() {
        ?>
        <div class="wrap">
            <h1>Vemetric</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'vemetric_settings' );
                $token = esc_attr( get_option( VMTRC_OPTION_TOKEN, '' ) );
                $host = esc_attr( get_option( VMTRC_OPTION_HOST, '' ) );
                $script_url = esc_attr( get_option( VMTRC_OPTION_SCRIPT_URL, '' ) );
                $track_pageviews = (bool) get_option( VMTRC_OPTION_TRACK_PAGEVIEWS, true );
                $track_outbound_links = (bool) get_option( VMTRC_OPTION_TRACK_OUTBOUND_LINKS, true );
                $track_data_attributes = (bool) get_option( VMTRC_OPTION_TRACK_DATA_ATTRIBUTES, true );
                $mask_paths = esc_attr( get_option( VMTRC_OPTION_MASK_PATHS, '' ) );
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="vmtrc_token">Project Token <span class="required">*</span></label></th>
                        <td>
                            <input name="<?php echo VMTRC_OPTION_TOKEN; ?>" type="text" id="vmtrc_token"
                                   value="<?php echo $token; ?>" class="regular-text code"
                                   placeholder="YOUR_PROJECT_TOKEN">
                            <p class="description">Find it in your Vemetric dashboard.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_host">Host</label></th>
                        <td>
                            <input name="<?php echo VMTRC_OPTION_HOST; ?>" type="text" id="vmtrc_host"
                                   value="<?php echo $host; ?>" class="regular-text code"
                                   placeholder="https://hub.vemetric.com">
                            <p class="description">The host to use for the Vemetric API. Necessary if you want to <a href="https://vemetric.com/docs/advanced-guides/using-a-proxy" target="_blank">use a proxy for tracking your data</a> via your own domain.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_script_url">Script URL</label></th>
                        <td>
                            <input name="<?php echo VMTRC_OPTION_SCRIPT_URL; ?>" type="text" id="vmtrc_script_url"
                                   value="<?php echo $script_url; ?>" class="regular-text code"
                                   placeholder="<?php echo VMTRC_SCRIPT_URL; ?>">
                            <p class="description">The URL of the Vemetric script. You can use a custom URL if you want to use a proxy to serve the script via your own domain.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_track_pageviews">Track pageviews</label></th>
                        <td>
                        <label>
                        <input id="vmtrc_track_pageviews" type="checkbox" name="<?php echo VMTRC_OPTION_TRACK_PAGEVIEWS; ?>"
                                value="1" <?php checked( $track_pageviews ); ?> />
                        <span class="description">Automatically record pageviews when the user navigates on your site.</span>
                        </label>
                    </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_track_outbound_links">Track outbound link clicks</label></th>
                        <td>
                        <label>
                        <input id="vmtrc_track_outbound_links" type="checkbox" name="<?php echo VMTRC_OPTION_TRACK_OUTBOUND_LINKS; ?>"
                                value="1" <?php checked( $track_outbound_links ); ?> />
                        <span class="description">Automatically record link clicks to external websites.</span>
                        </label>
                    </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_track_data_attributes">Track data attributes</label></th>
                        <td>
                        <label>
                        <input id="vmtrc_track_data_attributes" type="checkbox" name="<?php echo VMTRC_OPTION_TRACK_DATA_ATTRIBUTES; ?>"
                                value="1" <?php checked( $track_data_attributes ); ?> />
                        <span class="description">Enables <a href="https://vemetric.com/docs/product-analytics/tracking-custom-events#data-attributes" target="_blank">tracking of custom events via data attributes</a>.</span>
                        </label>
                    </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vmtrc_mask_paths">Mask paths</label></th>
                        <td>
                            <input name="<?php echo VMTRC_OPTION_MASK_PATHS; ?>" type="text" id="vmtrc_mask_paths"
                                   value="<?php echo $mask_paths; ?>" class="regular-text code">
                            <p class="description">A comma-separated list of paths to mask. For example: <code>/blog/*,/project/*/user/*</code>.</p>
                        </td>
                    </tr>
                </table>
                <p>Learn more about <a href="https://vemetric.com/docs/product-analytics/tracking-custom-events" target="_blank">tracking custom events</a> or <a href="https://vemetric.com/docs/product-analytics/user-identification" target="_blank">identifying authenticated users</a> in the Vemetric Documentation.</p>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}