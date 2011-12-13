<?php
session_start();
include_once '../properties/c2dm_account.php';
getAuthToken($c2dm_user_name,$c2dm_password,$c2dm_source,$c2dm_service);

function getAuthToken($username,$password,$source,$service) {   
    
    if (isset($_SESSION['google_auth_id']) && $_SESSION['google_auth_id'] != null)
        return $_SESSION['google_auth_id'];
    // get an authorization token
    $ch = curl_init();
    if (!ch) {
        return false;
    }
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
    $post_fields = "accountType=" . urlencode('GOOGLE')
            . "&Email=" . urlencode($userName)
            . "&Passwd=" . urlencode($password)
            . "&source=" . urlencode($source)
            . "&service=" . urlencode($service);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    if (strpos($response, '200 OK') === false) {
        return false;
    }
    // find the auth code
    preg_match("/(Auth=)([\w|-]+)/", $response, $matches);
    if (!$matches[2]) {
        return false;
    }
    $_SESSION['google_auth_id'] = $matches[2];
return $matches[2];
}
?>

