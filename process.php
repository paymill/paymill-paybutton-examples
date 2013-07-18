<?php
//
// Please download the PAYMILL PHP Wrapper at
// https://github.com/Paymill/Paymill-PHP
// and put the containing "lib" folder into your web-project
//

define('PAYMILL_API_HOST', 'https://api.paymill.com/v2/');
define('PAYMILL_API_KEY', 'f90d23dcc2a8f9639a45cb65dceabbd2');

if (isset($_POST['paymillToken'])) {
    $token = $_POST['paymillToken'];
    require 'lib/Services/Paymill/Transactions.php';
    $transactionsObject = new Services_Paymill_Transactions(PAYMILL_API_KEY, PAYMILL_API_HOST);

    $params = array(
        'amount' => '250', // E.g. "250" for 2.50 EUR!
        'currency' => 'EUR', // ISO 4217
        'token' => $token,
        'description' => 'Test Transaction'
    );

    $transaction = $transactionsObject->create($params);
    if (isset($transaction['status']) && ( $transaction['status'] == 'closed')) {
        echo '<strong>transaction successful!</strong>';
    }
}