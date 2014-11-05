<?php


class GcmPushNotification {
// Message to be sent
private $message;

// Set POST variables
private $url = 'https://android.googleapis.com/gcm/send';

private $fields = array(); 
//= array(
//                'registration_ids'  => array($_POST['registrationIDs']),
//                'data'              => array( "message" => $message ),
//                );

private $headers = array(); 
//= array( 
//                    'Authorization: key=' . $_POST['apiKey'],
//                    'Content-Type: application/json'
//                );


public function  __construct($message, $registrationIds) {
    $this->message = $message;
    
    $finaldeviceids = array();
    foreach ($registrationIds as $value) {
        array_push($finaldeviceids, $value['deviceid']);
    }
    $this->fields = array(
                'registration_ids'  => $finaldeviceids,
                'data'              => array( "message" => $this->message),
                );
    
    $this->headers = array( 
                    'Authorization: key=' . Yii::app()->params['gcmApiKey'],
                    'Content-Type: application/json'
                );

}

public function pushMessage() {
// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt( $ch, CURLOPT_URL, $this->url );

curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->headers);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $this->fields ) );

// Execute post
$result = curl_exec($ch);

// Close connection
curl_close($ch);
return $result;
}


}