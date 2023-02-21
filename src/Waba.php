<?php
namespace NotificationChannels\Waba;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\Waba\Exceptions\CouldNotSendNotification;

class Waba
{
    /**
     * @var WabaConfig
     */
    private $config;

    /**
     * Waba constructor.
     *
     * @param WabaConfig   $config
     */
    public function __construct(WabaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Send an sms message using the Waba Service.
     *
     * @param WabaMessage $message
     * @param string           $to
     * @return \Waba\MessageInstance
     */
    public function sendMessage(WabaMessage $message, $to)
    {
        $method = "messages";
        if ($message->method) {
            $method = $message->method;
        }
        if ($message->url) {
            $url           = $message->url;
            $token         = $message->token;
            $phoneNumberId = $message->phoneNumberId;
        } else {
            if (!$url = $this->config->getURL()) {
                throw CouldNotSendNotification::missingURL();
            }
            $token = $this->config->getToken();
        }

        $url           = trim($url);
        $token         = trim($token);
        $phoneNumberId = trim($phoneNumberId);
        $params = [
            'messaging_product' => "whatsapp",
            'recipient_type'    => "individual",
            'to'                => $to,
        ];

        if($message->type == "text"){
            $params += ['type' => $message->type];
            $params += ['text' => ['preview_url' => false, 'body' => trim($message->content)]];
        }

        if($message->type == "image"){
            $params += ['type' => $message->type];
            $params += ['image' => ['link' => trim($message->content)]];
        }

        if($message->type == "audio"){
            $params += ['type' => $message->type];
            $params += ['audio' => ['link' => trim($message->content)]];
        }

        if($message->type == "video"){
            $params += ['type' => $message->type];
            $params += ['video' => ['link' => trim($message->content), 'caption' => $message->caption]];
        }

        if($message->type == "document"){
            $params += ['type' => $message->type];
            $params += ['document' => ['link' => trim($message->content), 'caption' => $message->caption]];
        }

        if($message->type == "template"){
            $params += ['type' => $message->type];
            $params += ['template' => $message->template];
        }

        if($message->type == "interactive"){
            $params += ['type' => $message->type];
            $params += ['interactive' => $message->interactive];
        }

        $cliente = new Client;
        try {
            switch ($method) {
                case 'messages':
                    $response = $cliente->request(
                        'POST',
                        $url .$phoneNumberId. "/messages", [
                            'form_params' => $params,
                            'headers'   => [
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer '.$token,
                            ],
                            'timeout' => 25
                        ]);
                    break;
            }
            $html = (string) $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw CouldNotSendNotification::errorSending(Psr7\str($e->getResponse()));
            }
            throw CouldNotSendNotification::errorSending($e->getMessage());
        }

        return $response;
    }
}
