<?php
//Please modify the following variables
$amount = '250'; // E.g. "250" for 2.50 EUR!
$currency = 'EUR'; // ISO 4217
$description = $_SERVER["HTTP_REFERER"];
$privateApiKey = 'TESTPRIVATEKEY';

//If you want to use subscriptions please also edit the following variables
$subscription = false; //set to true if you want to use subscriptions
$interval = '1 MONTH'; //Defining how often the client should be charged. Format: number DAY | WEEK | MONTH | YEAR
$offerName = 'Testoffer'; //Your name for this offer

if (isset($_POST['paymillToken'])) {
    $token = $_POST['paymillToken'];

    $client = request(
        'clients/',
        array(),
        $privateApiKey
    );

    $payment = request(
        'payments/',
        array(
             'token' => $token,
             'client' => $client['id']
        ),
        $privateApiKey
    );

    $transaction = request(
        'transactions/',
        array(
             'amount' => $amount,
             'currency' => $currency,
             'client' => $client['id'],
             'payment' => $payment['id'],
             'description' => $description
        ),
        $privateApiKey
    );

    if (isset($transaction['status']) && ( $transaction['status'] == 'closed')) {
        echo '<strong>Transaction successful!</strong>';
    } else {
        echo '<strong>Transaction not successful!</strong>';
        var_dump($client);
        var_dump($payment);
        var_dump($transaction);
    }
}

    /**
     * Perform HTTP request to REST endpoint
     *
     * @param string $action
     * @param array $params
     * @param string $privateApiKey
     * @return array
     */
    function requestApi($action = '', $params = array(), $privateApiKey)
    {
        $curlOpts = array(
            CURLOPT_URL => "https://api.paymill.com/v2/" . $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_USERAGENT => 'Paymill-php/0.0.2',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO => realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'paymill.crt',
        );

        $curlOpts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
        $curlOpts[CURLOPT_USERPWD] = $privateApiKey . ':';

        $curl = curl_init();
        curl_setopt_array($curl, $curlOpts);
        $responseBody = curl_exec($curl);
        $responseInfo = curl_getinfo($curl);
        if ($responseBody === false) {
            $responseBody = array('error' => curl_error($curl));
        }
        curl_close($curl);

        if ('application/json' === $responseInfo['content_type']) {
            $responseBody = json_decode($responseBody, true);
        }

        return array(
            'header' => array(
                'status' => $responseInfo['http_code'],
                'reason' => null,
            ),
            'body' => $responseBody
        );
    }

    /**
     * Perform API and handle exceptions
     *
     * @param $action
     * @param array $params
     * @param string $privateApiKey
     * @return mixed
     */
    function request($action, $params = array(), $privateApiKey)
    {
        if (!is_array($params))
            $params = array();

        $responseArray = requestApi($action, $params, $privateApiKey);
        $httpStatusCode = $responseArray['header']['status'];
        if ($httpStatusCode != 200) {
            $errorMessage = 'Client returned HTTP status code ' . $httpStatusCode;
            if (isset($responseArray['body']['error'])) {
                $errorMessage = $responseArray['body']['error'];
            }
            $responseCode = '';
            if (isset($responseArray['body']['response_code'])) {
                $responseCode = $responseArray['body']['response_code'];
            }

            return array("data" => array(
                "error" => $errorMessage,
                "response_code" => $responseCode,
                "http_status_code" => $httpStatusCode
            ));
        }

        return $responseArray['body']['data'];
    }
