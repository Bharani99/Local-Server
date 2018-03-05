<?php


function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}
function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}



$data =json_decode(file_get_contents('php://input'),true);
//print_r(file_get_contents('php://input'));
//echo $data["location"];
//echo $data["group"];



$data['location']=(my_simple_crypt( $data['location'], 'e'));

$data['location']=strToHex($data['location']);

echo $data['location'];

echo (my_simple_crypt( $data['location'], 'd'));



$url = "http://192.168.1.101:8003/learn";


 
//print_r($data);
//Initiate cURL.

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$ch = curl_init($url);


$jsonDataEncoded = json_encode($data);
 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 
//Execute the request
$result = curl_exec($ch);

//echo $data;
echo $result;




?>
