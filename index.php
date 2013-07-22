<?php
require_once('setup.php');

$scriptName = 'transaction.php';
if ($subscription) {
    $scriptName = 'subscription.php';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <body>
        <form action="subscription.php" method="post">
            <script
                src="https://button.paymill.com/v1/"
                id="button"
                data-label="<?php echo $dataLabel; ?>"
                data-title="<?php echo $dataTitle; ?>"
                data-description="<?php echo $dataDescription; ?>"
                data-amount="<?php echo $amount; ?>"
                data-currency="<?php echo $currency; ?>"
                data-submit-button="Pay <?php echo ($amount / 100) . ' ' . $currency; ?>"
                data-public-key="<?php echo $publicKey; ?>">
            </script>
        </form>
    </body>
</html>
