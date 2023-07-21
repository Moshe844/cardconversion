<?php

$wsdl = "https://sandbox.usaepay.com/soap/gate/PWB52VVA/usaepay.wsdl";
$sourceKey = "_g6BALVW9vpPZ3jEqf5kwe4pIrqyvabY";
$pin = "1234";

// Function to get the SOAP client
function getClient($wsdl) {
    return new SoapClient($wsdl, array(
        'trace' => 1,
        'exceptions' => 1,
        'stream_context' => stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ))
    ));
}

// Function to get the token
function getToken($sourceKey, $pin) {
    $seed = time() . rand();
    return array(
        'SourceKey' => $sourceKey,
        'PinHash' => array(
            'Type' => 'sha1',
            'Seed' => $seed,
            'HashValue' => sha1($sourceKey . $seed . $pin)
        ),
        'ClientIP' => $_SERVER['REMOTE_ADDR']
    );
}

// Function to convert card to token
function convertCardToToken($client, $token, $creditCardData) {
    return $client->saveCard($token, $creditCardData);
}

// Check if the card data was sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cards'])) {
    $cardsArray = json_decode($_POST['cards'], true);

    $client = getClient($wsdl);
    $token = getToken($sourceKey, $pin);

    $result = array();

    foreach ($cardsArray as $cardData) {
        $cardInfo = explode(" ", trim($cardData));
        if (count($cardInfo) >= 2) {
            $creditCardData = array(
                'CardNumber' => $cardInfo[0],
                'CardExpiration' => $cardInfo[1],
                // Add other card data as needed
            );

            // Convert card to token
            $cctoken = convertCardToToken($client, $token, $creditCardData);
            $result[] = $cctoken;
        }
    }

    // Process the result as needed
    echo json_encode($result);
} else {
    // Handle invalid requests
    echo "Invalid request.";
}

?>
