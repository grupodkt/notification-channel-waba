<?php
namespace NotificationChannels\Waba;

class WabaConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * WabaConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get the url.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->config['url'];
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->config['token'];
    }

    /**
     * Get the phoneNumberId.
     *
     * @return string
     */
    public function getPhoneNumberId()
    {
        return $this->config['phoneNumberId'];
    }
}
