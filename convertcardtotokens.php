<?php

    try {

        $CreditCardData => array(
          'CardNumber' => '4444555566667779',
          'CardExpiration' => '0909',
          'CardCode' => '999'
          )
        );

      $token=$client->saveCard($token, $CreditCardData);


    }

    catch (SoapFault $e) {
      die("saveCard failed :" .$e->getMessage());
    }

    ?>