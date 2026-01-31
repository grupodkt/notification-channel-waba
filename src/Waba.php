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
        $params        = [
            'messaging_product' => "whatsapp",
            'recipient_type'    => "individual",
            'to'                => $to,
        ];

        // Si type está vacío o es null, usar "text" por defecto
        $messageType = $message->type ?: 'text';

        if ($messageType == "text") {
            $params += ['type' => 'text'];
            $params += ['text' => ['preview_url' => false, 'body' => trim($message->content)]];
        }

        if ($messageType == "image") {
            $params += ['type' => 'image'];
            $params += ['image' => ['link' => trim($message->content)]];
        }

        if ($messageType == "audio") {
            $params += ['type' => 'audio'];
            $params += ['audio' => ['link' => trim($message->content)]];
        }

        if ($messageType == "video") {
            $params += ['type' => 'video'];
            $params += ['video' => ['link' => trim($message->content), 'caption' => $message->caption]];
        }

        if ($messageType == "document") {
            $params += ['type' => 'document'];
            $params += ['document' => ['link' => trim($message->content), 'filename' => $message->filename,
                'caption'                         => $message->caption]];
        }

        if ($messageType == "template") {
            $params += ['type' => 'template'];
            $params += ['template' => $message->template];
        }

        if ($messageType == "interactive") {
            $params += ['type' => 'interactive'];
            $params += ['interactive' => $message->interactive];
        }

        $cliente = new Client;
        try {
            switch ($method) {
                case 'messages':
                    $response = $cliente->request(
                        'POST',
                        $url . $phoneNumberId . "/messages", [
                            'json' => $params,
                            'headers'     => [
                                'Authorization' => 'Bearer ' . $token,
                            ],
                            'timeout'     => 25,
                        ]);
                    break;
            }
            $html = (string) $response->getBody();
        } catch (RequestException $e) {
            \Log::error("WABA error: " . $e->getMessage());
            if ($e->hasResponse()) {
                throw CouldNotSendNotification::errorSending(Psr7\str($e->getResponse()));
            }
            throw CouldNotSendNotification::errorSending($e->getMessage());
        }

        return $response;
    }
}
