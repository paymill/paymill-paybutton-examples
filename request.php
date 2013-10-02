<?php
//Please modify the following variables
$amount = '250'; // E.g. "250" for 2.50 EUR!
$currency = 'EUR'; // ISO 4217
$description = 'Testdescription';
$privateApiKey = 'TESTPRIVATEKEY';

//If you want to use subscriptions please also edit the following variables
$subscription = false; //set to true if you want to use subscriptions
$interval = '1 MONTH'; //Defining how often the client should be charged. Format: number DAY | WEEK | MONTH | YEAR
$offerName = 'Testoffer'; //Your name for this offer

if (isset($_POST['paymillToken'])) {
    $token = $_POST['paymillToken'];

    require "lib/Services/Paymill/Clients.php";
    require "lib/Services/Paymill/Payments.php";

    $clientsObject = new Services_Paymill_Clients($privateApiKey, "https://api.paymill.com/v2/");
    $client = $clientsObject->create();

    $paymentsObject = new Services_Paymill_Payments($privateApiKey, "https://api.paymill.com/v2/");
    $payment = $paymentsObject->create(array(
        'token' => $token,
        'client' => $client['id']
    ));

    if($subscription) {
        require "lib/Services/Paymill/Offers.php";
        require "lib/Services/Paymill/Subscriptions.php";

        $offersObject = new Services_Paymill_Offers($privateApiKey, "https://api.paymill.com/v2/");
        $offer = $offersObject->create(array(
            'amount' => $amount,
            'currency' => $currency,
            'interval' => $interval,
            'name' => $offerName
        ));

        $subscriptionsObject = new Services_Paymill_Subscriptions($privateApiKey, "https://api.paymill.com/v2/");
        $subscription = $subscriptionsObject->create(array(
            'client' => $client['id'],
            'offer' => $offer['id'],
            'payment' => $payment['id']
        ));

        if (isset($subscription['offer']['subscription_count']['active']) && ($subscription['offer']['subscription_count']['active'])) {
            echo '<strong>Subscription successful!</strong>';
        } else {
            echo '<strong>Subscription not successful!</strong>';

        }

    } else {
        require 'lib/Services/Paymill/Transactions.php';
        $transactionsObject = new Services_Paymill_Transactions($privateApiKey, "https://api.paymill.com/v2/");

        $transaction = $transactionsObject->create(array(
            'amount' => $amount,
            'currency' => $currency,
            'client' => $client['id'],
            'payment' => $payment['id'],
            'description' => $description
        ));

        if (isset($transaction['status']) && ( $transaction['status'] == 'closed')) {
            echo '<strong>Transaction successful!</strong>';
        } else {
            echo '<strong>Transaction not successful!</strong>';
        }
    }
}
