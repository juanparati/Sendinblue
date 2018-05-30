<?php
namespace Juanparati\Sendinblue;


/**
 * Class SMSMessage.
 *
 * SMSMessage is a wrapper for Laravel notifications messages
 *
 * @package Juanparati\Sendinblue
 */
class SMSMessage
{

    /**
     * Template instance.
     *
     * @var SMS
     */
    protected $instance;


    /**
     * TemplateMessage constructor.
     *
     * @param int $template_id
     */
    public function __construct(string $content, string $type = 'transactional')
    {
        $this->instance = app()->make(SMS::class);
        $this->instance->message($content);
        $this->instance->type($type);
    }


    /**
     * Call Template instance methods.
     *
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        call_user_func_array([$this->instance, $name], $arguments);

        return $this;
    }


    /**
     * Send message.
     *
     * @return int
     */
    public function send() : int
    {
        return $this->instance->send();
    }

}