<?php

namespace App\Classe;

use Mailjet\Resources;
use Mailjet\Client;

class Mail
{   
    private $api_key_secret = '681c1602242184de5e4a3cff8f86bf00';
    private $api_key_public = '060707e20c8d491141833dc233cfc9cd';

    
    public function send( $to_email, $to_name, $subject, $content )
    {   // je passe a la fonction send() les paramètres de la fonction __construct() 
        $mj = new Client($this->api_key_public, $this->api_key_secret, true, ['version' => 'v3.1']);     
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "hevow34116@oniecan.com",
                        'Name' => "boutiquecommerce"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ]
                    ],
                    'TemplateID' => 4657412,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                       
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success(); 
        // && var_dump($response->getData());
      
    }
}
