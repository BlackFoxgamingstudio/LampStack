<?php

add_action( 'admin_init', 'toko_theme_updater' );
function toko_theme_updater() {
    if ( !class_exists("TOKO_Theme_Updater") ) return;
    $version = defined('THEME_VERSION') ? THEME_VERSION : '1.0';
    $license = trim( get_option( 'theme_license_key' ) );
    $toko_updater = new TOKO_Theme_Updater( array(
            'version'           => $version,
            'license'           => $license,
        )
    );
}

function toko_theme_updater_menu() {
    add_theme_page( __( 'Theme Update', 'tokopress' ), __( 'Theme Update', 'tokopress' ), 'manage_options', 'theme-updater', 'toko_theme_updater_page' );
}
add_action('admin_menu', 'toko_theme_updater_menu', 10);

function toko_theme_updater_page() {
    $license    = get_option( 'theme_license_key' );
    ?>
    <div class="wrap">
        <h2><?php _e('Theme Update Options', 'tokopress'); ?></h2>
        <form method="post" action="options.php">

            <?php settings_fields('toko_theme_license'); ?>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License Key', 'tokopress'); ?>
                        </th>
                        <td>
                            <input id="theme_license_key" name="theme_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
                            <label class="description" for="theme_license_key"><?php _e('Enter your license key', 'tokopress'); ?></label>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p><?php _e( 'Note: If you purchase this theme from ThemeForest, License Key is your Purchase Code.', 'tokopress' ); ?></p>

            <?php submit_button(); ?>

        </form>

    <?php
}

function toko_theme_updater_register() {
    register_setting('toko_theme_license', 'theme_license_key', 'toko_theme_updater_sanitize' );
}
add_action('admin_init', 'toko_theme_updater_register');

function toko_theme_updater_sanitize( $new ) {
    $old = get_option( 'theme_license_key' );
    if( $old && $old != $new ) {
        delete_transient( sanitize_key( get_template() ) . '-update-response' );
    }
    return $new;
}

class TOKO_Theme_Updater {
    private $remote_api_url;
    private $request_data;
    private $response_key;
    private $theme_slug;
    private $license_key;
    private $version;
    private $author;

    function __construct( $args = array() ) {
        $args = wp_parse_args( $args, array(
            'remote_api_url' => 'http://www.tokopress.com/api/',
            'request_data'   => array(),
            'theme_slug'     => get_template(),
            'item_name'      => '',
            'license'        => '',
            'version'        => '',
            'author'         => ''
        ) );
        extract( $args );

        $theme                = wp_get_theme( sanitize_key( $theme_slug ) );
        $this->license        = $license;
        $this->item_name      = $item_name;
        $this->version        = ! empty( $version ) ? $version : $theme->get( 'Version' );
        $this->theme_slug     = sanitize_key( $theme_slug );
        $this->author         = $author;
        $this->remote_api_url = $remote_api_url;
        $this->response_key   = $this->theme_slug . '-update-response';
    
        add_filter( 'site_transient_update_themes', array( &$this, 'theme_update_transient' ) );
        add_filter( 'delete_site_transient_update_themes', array( &$this, 'delete_theme_update_transient' ) );

        add_action( 'load-update-core.php', array( &$this, 'delete_theme_update_transient' ) );
        add_action( 'load-themes.php', array( &$this, 'delete_theme_update_transient' ) );
        add_action( 'load-appearance_page_theme-updater', array( &$this, 'delete_theme_update_transient' ) );

        add_action( 'load-update-core.php', array( &$this, 'load_themes_screen' ) );
        add_action( 'load-themes.php', array( &$this, 'load_themes_screen' ) );
        add_action( 'load-theme-editor.php', array( &$this, 'load_themes_screen' ) );
        add_action( 'load-appearance_page_theme-updater', array( &$this, 'load_themes_screen' ) );
        add_action( 'load-appearance_page_options-framework', array( &$this, 'load_themes_screen' ) );
    }

    function load_themes_screen() {
        add_thickbox();
        wp_enqueue_script( 'theme-preview' );
        add_action( 'admin_notices', array( &$this, 'update_nag' ) );
    }

    function update_nag() {
        $theme = wp_get_theme( $this->theme_slug );

        $api_response = get_transient( $this->response_key );

        if( false === $api_response )
            return;

        $update_url = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $this->theme_slug ), 'upgrade-theme_' . $this->theme_slug );
        $update_onclick = ' onclick="if ( confirm(\'' . esc_js( __( "Updating this parent theme will lose any customizations you have made. Please consider using child theme for modifications. 'Cancel' to stop, 'OK' to update.", 'tokopress' ) ) . '\') ) {return true;}return false;"';

        if ( version_compare( $this->version, $api_response->new_version, '<' ) ) {

            $license = get_option( 'theme_license_key' );
            echo '<div id="update-nag">';
                if ( $license ) {
                    printf( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox thickbox-preview" title="%4s">Check out what\'s new</a>. <a href="%5$s">Edit your license</a>. <a href="%6$s"%7$s><b>Update Now!</b></a>',
                        $theme->get( 'Name' ),
                        $api_response->new_version,
                        $api_response->url,
                        $theme->get( 'Name' ),
                        admin_url('themes.php?page=theme-updater'),
                        $update_url,
                        $update_onclick
                    );
                }
                else {
                    printf( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox thickbox-preview" title="%4s">Check out what\'s new</a>. <a href="%5$s">Enter your license to enable auto-update</a>.',
                        $theme->get( 'Name' ),
                        $api_response->new_version,
                        $api_response->url,
                        $theme->get( 'Name' ),
                        admin_url('themes.php?page=theme-updater')
                    );
                }
            echo '</div>';
            echo '<div id="' . $this->theme_slug . '_' . 'changelog" style="display:none;">';
                echo wpautop( $api_response->sections['changelog'] );
            echo '</div>';
        }
    }

    function theme_update_transient( $value ) {
        $update_data = $this->check_for_update();
        if ( $update_data ) {
            $value->response[ $this->theme_slug ] = $update_data;
        }
        return $value;
    }

    function delete_theme_update_transient() {
        delete_transient( $this->response_key );
    }

    function check_for_update() {

        $theme = wp_get_theme( $this->theme_slug );

        $update_data = get_transient( $this->response_key );
        if ( false === $update_data ) {
            $failed = false;

            // if( empty( $this->license ) )
            //  return false;

            $api_params = array(
                'action'        => 'get_version',
                'license'       => $this->license,
                'name'          => $this->item_name,
                'slug'          => $this->theme_slug,
                'author'        => $this->author,
                'url'           => home_url()
            );
            
            $response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'sslverify' => false, 'headers' => array( 'Accept-Encoding' => '' ), 'body' => $api_params ) );
            // print_r( $response );

            // make sure the response was successful
            if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
                $failed = true;
            }

            $update_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( ! is_object( $update_data ) ) {
                $failed = true;
            }

            // if the response failed, try again in 30 minutes
            if ( $failed ) {
                $data = new stdClass;
                $data->new_version = $this->version;
                set_transient( $this->response_key, $data, strtotime( '+30 minutes' ) );
                return false;
            }

            // if the status is 'ok', return the update arguments
            if ( ! $failed ) {
                if ( isset ( $update_data->sections ) )
                    $update_data->sections = maybe_unserialize( $update_data->sections );
                set_transient( $this->response_key, $update_data, strtotime( '+12 hours' ) );
            }
        }

        if ( version_compare( $this->version, $update_data->new_version, '>=' ) ) {
            return false;
        }

        return (array) $update_data;
    }
}
