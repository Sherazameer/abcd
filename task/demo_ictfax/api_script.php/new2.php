<?php

// Replace these variables with your actual values
$authToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaSIsImlhdCI6MTczMzIwOTU5NiwibmJmIjoxNzMzMjA5NTk2LCJleHAiOjE3NjQzMTM1OTYsInVzZXJfaWQiOiI1IiwidGVuYW50X2lkIjoiMyIsInVzZXJuYW1lIjoiRGFuaXNoIiwiaXNfYWRtaW4iOiIwIiwiaXNfdGVuYW50IjoiMSIsImFwaS12ZXJzaW9uIjoiMS4wIiwibWZhX2VuYWJsZWQiOiIwIiwiYXV0aF92ZXJpZnkiOiIwIn0.M9W0ahaTvgLTilkRtSc9nNjPE3WS1SmoWsmqYHAkGcrlW6PK7EW8-XilJPFAzFEy74Mwvfnb-Suic4PN2_4iQxlNe8aTfEjKNkHDU_lyWmO1mZVXprsXP2iO4gD1-CilN7nglVNAUToiiSRRPMkgsoMYaRfCpvMmnIao4UGhIlU';
// Function to send a POST request
function sendPostRequest($url, $data, $bearerToken = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = ['Content-Type: application/json'];
    if ($bearerToken) {
        $headers[] = 'Authorization: Bearer ' . $bearerToken;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

// Function to send a GET request
function sendGetRequest($url, $bearerToken) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $bearerToken
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

// Function to send a PUT request
function sendPutRequest($url, $data, $bearerToken) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $bearerToken
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}


if (isset($authToken)) {
    echo "Authentication successful. Token: " . $authToken . PHP_EOL;

    // Step 2: Assign DID
    // $assignDidUrl = "https://demo.ictfax.com/api/accounts/8/users/5";
    // $assignDidData = ["user_id" => "5"];
    // $assignDidResponse = sendPutRequest($assignDidUrl, $assignDidData, $authToken);
    // echo "Assign DID Response: " . $assignDidResponse . PHP_EOL;

    // Step 3: Fetch All DIDs
    // $fetchDidsUrl = "https://demo.ictfax.com/api/dids";
    // $fetchDidsResponse = sendGetRequest($fetchDidsUrl, $authToken);
    // echo "Fetch All DIDs Response: " . $fetchDidsResponse . PHP_EOL;

    // // Step 4: Forward DID
    $forwardDidUrl = "https://demo.ictfax.com/api/accounts/8";
    $forwardDidData = ["email" => "dh1460630@gmail.com"];
    $forwardDidResponse = sendPutRequest($forwardDidUrl, $forwardDidData, $authToken);
    echo "Forward DID Response: " . $forwardDidResponse . PHP_EOL;
} else {
    echo "Authentication failed: " . $authResponse . PHP_EOL;
}

?>
