<?php

//
// Please download the PAYMILL PHP Wrapper at
// https://github.com/Paymill/Paymill-PHP
// and put the containing "lib" folder into your web-project
//
require_once('lib/Services/Paymill/Payments.php');
require_once('lib/Services/Paymill/Offers.php');
require_once('lib/Services/Paymill/Subscriptions.php');
require_once('lib/Services/Paymill/Clients.php');

require_once('setup.php');


if (isset($_POST['paymillToken'])) {
    $token = $_POST['paymillToken'];

    $offersObject = new Services_Paymill_Offers($secretKey, $apiUrl);
    $offer = $offersObject->create(array(
        'amount' => $amount,
        'currency' => $currency,
        'interval' => $interval,
        'name' => $offerName
    ));

    $clientsObject = new Services_Paymill_Clients($secretKey, $apiUrl);
    $client = $clientsObject->create(array(
        'email' => $email,
        'description' => $clientDescription
    ));

    $paymentsObject = new Services_Paymill_Payments($secretKey, $apiUrl);
    $creditcard = $paymentsObject->create(array(
        'token' => $token,
        'client' => $client['id']
    ));

    $subscriptionsObject = new Services_Paymill_Subscriptions($secretKey, $apiUrl);
    $subscription = $subscriptionsObject->create(array(
        'client' => $client['id'],
        'offer' => $offer['id'],
        'payment' => $creditcard['id']
    ));

    if (isset($subscription['offer']['subscription_count']['active']) && ($subscription['offer']['subscription_count']['active'])) {
        echo '<strong>subscription successful!</strong>';
    }
}