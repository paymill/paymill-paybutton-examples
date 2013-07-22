<?php
//
// Please download the PAYMILL PHP Wrapper at
// https://github.com/Paymill/Paymill-PHP
// and put the containing "lib" folder into your web-project
//

require_once('setup.php');


if (isset($_POST['paymillToken'])) {
    $token = $_POST['paymillToken'];
    require 'lib/Services/Paymill/Transactions.php';
    $transactionsObject = new Services_Paymill_Transactions($secretKey, $apiUrl);

    $params = array(
        'amount' => $amount, // E.g. "250" for 2.50 EUR!
        'currency' => $currency, // ISO 4217
        'token' => $token,
        'description' => $description
    );

    $transaction = $transactionsObject->create($params);
    if (isset($transaction['status']) && ( $transaction['status'] == 'closed')) {
        echo '<strong>transaction successful!</strong>';
    }
}