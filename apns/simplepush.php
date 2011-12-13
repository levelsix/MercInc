<?php

$deviceToken = 'ea29b67dbb203e97fd633224007a6bad31a519aa0c21c7049d7ecf3f679a62f9';

// Put your device token here (without spaces):
//$deviceToken = '94130ac15488110edab5feaedbd827ac96062ac72be19faf36f63c21a4d9f460';
function sendMessage($deviceToken) {

// Put your private key's passphrase here:
    $passphrase = 'pushchat';

// Put your alert message here:
    $message = 'My first push notification! is here';

////////////////////////////////////////////////////////////////////////////////

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

    echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
    $body['aps'] = array(
        'alert' => $message,
        'sound' => 'default'
    );

// Encode the payload as JSON
    $payload = json_encode($body);

// Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));

    if (!$result)
        echo 'Message not delivered' . PHP_EOL;
    else
        echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
    fclose($fp);
}