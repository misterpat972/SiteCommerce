<?php

namespace App\Classe;

use Mailjet\Resources;
use Mailjet\Client;

class Mail
{   
    private $api_key_secret = '3ee2eb10ff6d73156a193e15c70adabd';
    private $api_key_public = '060707e20c8d491141833dc233cfc9cd';
    private $to_email;
    private $to_name;
    private $subject;
    private $content;

    // public function __construct($to_mail, $subject, $to_name, $content)
    // {
    //     $this->to_email = $to_mail;
    //     $this->subject = $subject;
    //     $this->to_name = $to_name;
    //     $this->content = $content;

    //     // $this->message = $message;
    //     // $this->headers = $headers;
    // }

    public function send( $to_email, $to_name, $subject, $content )
    {   // je passe a la fonction send() les paramètres de la fonction __construct() 
        $mj = new Client($this->api_key_public, $this->api_key_secret, true, ['version' => 'v3.1']);     
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "misterpat972@gmail.com",
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
        $response->success() && dd($response->getData());
    }
}
