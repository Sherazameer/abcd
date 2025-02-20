<?php

$bearerToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaSIsImlhdCI6MTczMzExNDcxMSwibmJmIjoxNzMzMTE0NzExLCJleHAiOjE3NjQyMTg3MTEsInVzZXJfaWQiOiIxIiwidGVuYW50X2lkIjoiMSIsInVzZXJuYW1lIjoiYWRtaW4iLCJpc19hZG1pbiI6IjEiLCJpc190ZW5hbnQiOiIxIiwiYXBpLXZlcnNpb24iOiIxLjAiLCJtZmFfZW5hYmxlZCI6IjAiLCJhdXRoX3ZlcmlmeSI6IjAifQ.g-lBODmeivjVtqE2-BAltrhT0sKhwAypGotB1mt_mxNwb8uYc0voukhh-jz3TNJSeKm6pJ8SUK9Gg-CPi4bb9VcXT_KtL8BUR6sAgYyQBCKMBMIs6pI-XVMrQbrAoK7cdj48GiTCefIMCMIQFOisa9FFhVoLVInrkTu2EXkoYHg';

function sendPostRequest($url, $data, $bearerToken) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $bearerToken 
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    
    curl_close($ch);
    return $response;
}

function sendGetRequest($url, $bearerToken) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $bearerToken 
    ]);
    
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    
    curl_close($ch);
    return $response;
}

function sendFax($bearerToken) {
    $url = "https://demo.ictfax.com/api/programs/sendfax";
    $data = [
     'document_id' => 17
    ];
    
    $response = sendPostRequest($url, $data, $bearerToken);
   createTransmission($bearerToken , $response);
}

function createTransmission($bearerToken , $program_id) {
    $url = "https://demo.ictfax.com/api/transmissions";
    $data = [
        "tenant_id" => 1,
        "title" => "title",
        "origin" => "origin",
        "contact_id" => 22,
        "account_id" => 1,
        "service_flag" => 1, 
        "program_id" => $program_id,
        "direction" => "direction",
        "status" => "status",
        "response" => "response"
    ];
    
    $response = sendPostRequest($url, $data, $bearerToken);
    echo "Transmission Created: $response";
    sendTransmissionById($response, $bearerToken);
}

function sendTransmissionById($transmissionId, $bearerToken) {
    $url = "https://demo.ictfax.com/api/transmissions/{$transmissionId}/send";
    $data = [
    ];
    
    $response = sendPostRequest($url, $data, $bearerToken);
    echo "Transmission Sent: $response";
}

function getTransmissions($bearerToken) {
    $url = "https://demo.ictfax.com/api/transmissions?service_flag=2&direction=outbound";
    
    $response = sendGetRequest($url, $bearerToken);
    echo "Outbounds Response: $response";
}

function getCompletedTransmissions($bearerToken) {
    $url = "https://demo.ictfax.com/api/transmissions?service_flag=2&status=completed&origin=faxtoemail";
    
    $response = sendGetRequest($url, $bearerToken);
    echo "Inbounds Transmissions: $response";
}

 sendFax($bearerToken); 
 getTransmissions($bearerToken); 
 getCompletedTransmissions($bearerToken); 

?>
