<?php
/**
 * Plugin Name: Parcel Ship API Connect Plugin
 * Plugin URI: http://fiverr.com/wp_expert_
 * Description: Parcel Ship API Connect Plugin.  
 * Version: 1.0.0
 * Author: Ashik72
 * Author URI: http://ashik72.me
 */

/*Constants*/

define('PID', get_option('parcel_ship_client_id'));
define('PAPI', get_option('parcel_ship_api'));
define('CKEY', get_option('consumer_key'));
define('CSEC', get_option('consumer_secret'));
define('APIURL', 'http://212.84.73.213/~dev/ParcelShip/api_post_method.php');

/*Constants*/


add_action('woocommerce_payment_complete', 'custom_process_order', 10, 1);
function custom_process_order($order_id) {
    $order = new WC_Order( $order_id );
    $myuser_id = (int)$order->user_id;
    $user_info = get_userdata($myuser_id);
    $items = $order->get_items();


    $ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => plugins_url('receive.php', __FILE__),
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => array(
        'order_id' => $order_id,
        'site_url' => site_url(),
        'pid' => PID,
        'papi' => PAPI,
        'ckey' => CKEY,
        'csec' => CSEC,
        'apiurl' => APIURL,


    )
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
curl_close($ch);


    return $order_id;
}



/*Options*/





add_action( 'admin_menu', 'fulfillment_admin_menu' );
function fulfillment_admin_menu() {
   // add_options_page( 'My Plugin', 'My Plugin', 'manage_options', 'my-plugin', 'my_options_page' );
add_options_page( 'Parcel Ship Options', 'Parcel Ship', 'manage_options', 'parcel_ship', 'parcel_ship_menu_page' );

}

add_action( 'admin_init', 'parcel_ship_admin_init' );
function parcel_ship_admin_init() {
    register_setting( 'parcel_ship-settings-group', 'parcel_ship_client_id' );
    register_setting( 'parcel_ship-settings-group', 'parcel_ship_api' );
    register_setting( 'parcel_ship-settings-group', 'consumer_key' );
    register_setting( 'parcel_ship-settings-group', 'consumer_secret' );


    add_settings_section( 'parcel_ship_settings_section', 'Parcel Ship Settings', 'parcel_ship_settings_callback', 'parcel_ship' );
    add_settings_field( 'parcel_ship_client_id-field', 'Parcel Ship Client ID', 'parcel_ship_client_id_callback', 'parcel_ship', 'parcel_ship_settings_section' );

    add_settings_field( 'parcel_ship_api-field', 'Parcel Ship API', 'parcel_ship_api_callback', 'parcel_ship', 'parcel_ship_settings_section' );
    
    add_settings_field( 'consumer_key-field', 'Consumer Key', 'consumer_key_callback', 'parcel_ship', 'parcel_ship_settings_section' );

    add_settings_field( 'consumer_secret-field', 'Consumer Secret', 'consumer_secret_callback', 'parcel_ship', 'parcel_ship_settings_section' );


}

function parcel_ship_settings_callback() {
    echo 'Please fill up Parcel Ship Client ID, Parcel Ship API values from \'Parcel Ship\' and Consumer Key, Consumer Secret from Users > Your Profile (after genereting). If you need any support or advice regarding the PSHIP API, please email 
info@parcelship.co.uk . </br> ';
echo '<b><i>cURL status: </b></i>', function_exists('curl_version') ? '<strong>Enabled</strong>' : '<strong>Disabled</strong>';
echo "</br> <i>(make sure cURL is enabled)</i>";
}

function parcel_ship_client_id_callback() {
    $setting = esc_attr( get_option( 'parcel_ship_client_id' ) );
    echo "<input type='text' name='parcel_ship_client_id' value='$setting' />";
}

function parcel_ship_api_callback() {
    $setting = esc_attr( get_option( 'parcel_ship_api' ) );
    echo "<input type='text' name='parcel_ship_api' value='$setting' />";
}

function consumer_key_callback() {
    $setting = esc_attr( get_option( 'consumer_key' ) );
    echo "<input type='text' name='consumer_key' value='$setting' />";
}

function consumer_secret_callback() {
    $setting = esc_attr( get_option( 'consumer_secret' ) );
    echo "<input type='text' name='consumer_secret' value='$setting' />";
}



function parcel_ship_menu_page() {
    ?>
    <div class="wrap">
        <h2>Parcel Ship Options</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'parcel_ship-settings-group' ); ?>
            <?php do_settings_sections( 'parcel_ship' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/*Options*/