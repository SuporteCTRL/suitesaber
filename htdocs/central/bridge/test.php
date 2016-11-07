<?php
// Insert the NuSOAP code
require_once(dirname(__FILE__)."/../nusoap/nusoap.php");
// This is location of the remote service
$client = new nusoap_client('http://localhost:9090/central/bridge/endpointusers.php');

// Check for any errors from the remote service
$err = $client->getError();
if ($err) {
    echo '<p><b>Error: ' . $err . '</b></p>';
}

// Call the SOAP method on the remote service
$result = $client->call(
    'searchUsersById',                    // method name
    array('id' =>'ABX-6272,ABX-6362,')
);

echo $client->request;
echo $client->response;

print_r($result);


?>

