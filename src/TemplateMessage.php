<?php
namespace Juanparati\Sendinblue;


/**
 * Class TemplateMessage.
 *
 * TemplateMessage is a wrapper for Laravel notifications messages
 *
 * @package Juanparati\Sendinblue
 *
 * @method $this from($address, $name = null)
 * @method $this to($address, $name = null)
 * @method $this cc($address, $name = null)
 * @method $this bcc($address, $name = null)
 * @method $this replyTo($address, $name = null)
 * @method $this setAddress($address, $name = null, $property = 'to')
 * @method $this attach($file, array $options = [])
 * @method $this tag(...$tag)
 * @method $this attachURL($file)
 * @method $this attribute($name, $value)
 * @method $this attributes(array $attributes)
 */
class TemplateMessage
{

    /**
     * Template instance.
     *
     * @var Template
     */
    protected $instance;


    /**
     * Template Id.
     *
     * @var int
     */
    protected $template_id;


    /**
     * TemplateMessage constructor.
     *
     * @param int $template_id
     */
    public function __construct(int $template_id)
    {
        $this->template_id = $template_id;
        $this->instance = app()->make(Template::class);
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
     * @return string   The message ID
     * @throws Exceptions\TransportException
     */
    public function send() : string
    {
        return $this->instance->send($this->template_id);
    }

}
