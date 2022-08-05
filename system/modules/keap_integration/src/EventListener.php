<?php

namespace KeapIntegration;

use Contao\System;

class EventListener extends System
{
    public function refreshKeapToken(): void
    {
        // Retrieve the token from your saved file
        $token_file = file_get_contents('token.txt');
        $token = unserialize($token_file);

        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
          'clientId'     => 'IoAJ9zFbZnszZHWkTxp7vFj5zg0TII2g',
          'clientSecret' => 'xS3oRvWe5kgcXjdG',
          'redirectUri'  => 'https://framework.brightcloudstudioserver.com/keap_auth.php',
        ));

        $infusionsoft->setToken($token);

        // Refresh the token
        $refreshed_token = $infusionsoft->refreshAccessToken();
        $_SESSION['token'] = serialize($refreshed_token);

        // Save it to the token file 
        $file_handle = fopen('token.txt', 'w');
        fwrite($file_handle, serialize($refreshed_token));
        
        // Testing the controller log
        \Controller::log('CRON: Keap Token Refreshed - ' + refreshed_token ,
            __CLASS__ . '::' . __FUNCTION__,
            'GENERAL'
        );
    }
}
