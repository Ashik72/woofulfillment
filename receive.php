<?php 

if(empty($_POST)) {

	die('Error!');
} else {


/*LOG*/
/*$myfile = fopen("woo3.txt", "w") or die("Unable to open file!");

$id = $_POST['order_id'];
fwrite($myfile, print_r($id, TRUE));
fclose($myfile);*/
/*LOG*/



//var_dump($_POST);




/*API*/


require_once( 'lib/woocommerce-api.php' );

$options = array(
	'debug'           => true,
	'return_as_array' => false,
	'validate_url'    => false,
	'timeout'         => 30,
	'ssl_verify'      => false,
);

try {

	    $client = new WC_API_Client( $_POST['site_url'], $_POST['ckey'], $_POST['csec'], $options );


$id = $_POST['order_id'];

//echo "{$id}";

foreach ($client->orders->get($id)->order->line_items as $key => $value) {
	# code...
	$pro_sku[] = $value->sku;
	$pro_quan[] = $value->quantity;

}
	
/*Process*/
$client_id = $_POST['pid'];
    $api_key = $_POST['papi'];
    $delivery_option = $client->orders->get($id)->order->shipping_methods;
    $number = $client->orders->get($id)->order->total_line_items_quantity;
    $po_number = $client->orders->get($id)->order->shipping_methods;
    $s_name = $client->orders->get($id)->order->shipping_address->first_name." ".$client->orders->get($id)->order->shipping_address->last_name;
    $s_company = $client->orders->get($id)->order->shipping_address->company;
    $s_l1 = $client->orders->get($id)->order->shipping_address->address_1;
    $s_l2 = $client->orders->get($id)->order->shipping_address->address_2;
    $s_town = $client->orders->get($id)->order->shipping_address->city;
    $s_county = $client->orders->get($id)->order->shipping_address->state;
    $s_country = $client->orders->get($id)->order->shipping_address->country;
    $tel = $client->orders->get($id)->order->billing_address->phone;
    $email = $client->orders->get($id)->order->billing_address->email;
 
echo $email;


	$ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => $_POST['apiurl'],
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => array(
        'clientid' => $client_id,
        'api_key' => $api_key,
        'delivery_option' => $delivery_option,
        'number' => $number,
        'po_number' => $po_number,
        's_name' => $s_name,
        's_company' => $s_company,
        's_l1' => $s_l1,
        's_l2' => $s_l2,
        's_town' => $s_town,
        's_county' => $s_county,
        's_country' => $s_country,
        'tel' => $tel,
        'email' => $email,
        'sku_post' => $pro_sku,
        'qty_post' => $pro_quan,
       

    )
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
print_r($result);
curl_close($ch);

/*Process end*/



} catch ( WC_API_Client_Exception $e ) {

	
	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		
	}
}


/*API*/


}