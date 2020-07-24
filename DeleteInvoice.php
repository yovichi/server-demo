<?php 
    //API Docs: https://developer.paypal.com/docs/api/invoicing/v2

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    // Generate API Access Token
    $ch = curl_init();
    $clientId = "AbKQVNDCSq3I0kUYaiq2wsA98yoZcThOhwcXJLpyJzXJU9j2QJ4mDElgRAO0rQamXjp_Dawet0NOMExS";
    $secret = "EDaLuf4MKKVA_86sXkAc8Nr7Lo3Y3jkKb3IjggR8sxjSjmxOLl3oYPugI_JguxU0kN7jZsZOuJ5zN_is";

    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $result = curl_exec($ch);
    curl_close($ch);

    if(empty($result))
        die("Error: No response.");
    else
    {
        $json = json_decode($result);
        $access_token = $json->access_token;

        // echo $access_token;

        // Delete for Paypal Invoice

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v2/invoicing/invoices/INV2-XD4P-JE32-N5Y9-VR96");      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$access_token
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;

    }

    
?>