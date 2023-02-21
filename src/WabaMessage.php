<?php
namespace NotificationChannels\Waba;

class WabaMessage
{
    /**
     * The waba params. (Required)
     *
     * @var string
     */
    public $type;

    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The waba url. (optional)
     *
     * @var string
     */
    public $url;

    /**
     * The waba token. (optional)
     *
     * @var string
     */
    public $token;

    /**
     * The phone number id the account. (optional)
     *
     * @var string
     */
    public $phoneNumberId;

    /**
     * The waba method. (optional)
     *
     * @var string
     */
    public $method;

    /**
     * The waba caption. (optional)
     *
     * @var string
     */
    public $caption;

    /**
     * The waba template.
     *
     * @var string
     */
    public $template;

    /**
     * The waba interactive.
     *
     * @var string
     */
    public $interactive;

    /**
     * Create a message object.
     * @param string $content
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the type (text,images,video,etc).
     *
     * @param  string $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the waba url.
     *
     * @param  string $url
     */
    public function url($url)
    {
        $this->url = $url;
    }

    /**
     * Set the waba token.
     *
     * @param  string $token
     */
    public function token($token)
    {
        $this->token = $token;
    }

    /**
     * Set the waba phoneNumberId.
     *
     * @param  string $phoneNumberId
     */
    public function phoneNumberId($phoneNumberId)
    {
        $this->phoneNumberId = $phoneNumberId;
    }

    /**
     * Set the waba method.
     *
     * @param  string $method
     */
    public function method($method)
    {
        $this->method = $method;
    }

    /**
     * Set the waba caption.
     *
     * @param  string $caption
     */
    public function caption($caption = '')
    {
        $this->caption = $caption;
    }

    /**
     * Set the waba template.
     *
     * @param  string $template
     */
    public function template($template)
    {
        $this->template = $template;
    }

    /**
     * Set the waba interactive.
     *
     * @param  string $interactive
     */
    public function interactive($interactive)
    {
        $this->interactive = $interactive;
    }
}
