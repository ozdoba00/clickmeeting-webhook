<?php

function recursive_implode(array $array, $glue = ', ', $include_keys = false, $trim_all = true)
{
	$glued_string = '';

	// Recursively iterates array and adds key/value to glued string
	array_walk_recursive($array, function($value, $key) use ($glue, $include_keys, &$glued_string)
	{
		$include_keys and $glued_string .= $key.$glue;
		$glued_string .= $value.$glue;
	});

	// Removes last $glue from string
	strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));

	// Trim ALL whitespace
	$trim_all and $glued_string = preg_replace("/(\s)/ixsm", ' ', $glued_string);

	return (string) $glued_string;
}

header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'request.log', $req_dump );


if($json = json_decode(file_get_contents("php://input"), true)){
   $products_json = $json;
}

$jsonfile = file_get_contents("dupa.json");
include_once 'ClickMeetingClient.php';
$client = new ClickMeetingRestClient(array('api_key' => 'eue97d5d1ee564d39b6b2576ae6fc9331223135b41'));
$products = $client->conferences();




for ($i=0; $i <= sizeof($products_json['products']); $i++) { 
  $email_address = $products_json['email'];
  $firstName = $products_json['deliveryAddress']['firstname'];
  $lastName = $products_json['deliveryAddress']['lastname'];
  $productName = $products_json['products'][$i]['name'];



  foreach ($products as $value) {
    
    if(strcmp($value->name,$productName)==0){


      print_r($client->addConferenceRegistration($value->id, array(
        'registration' => array(
          1 => $firstName,
          2 => $lastName,
          3 => $email_address
        ),
        'confirmation_email' => array(
            'enabled' => 1,
            'lang' => 'pl',
        )
      )));
    }
    
  }
}

die;


?>