<?php

namespace Juanparati\Sendinblue;

use Illuminate\Support\Traits\Macroable;

use Juanparati\Sendinblue\Contracts\SMSTransport as SMSTransportContract;


/**
 * Class SendinblueSMS.
 *
 * Sendinblue Transactional SMS.
 *
 * @package Juanparati\Sendinblue
 */
class SMS
{

    use Macroable;


    /**
     * Template model.
     *
     * @var SMSModel
     */
    protected $model;


    /**
     * Template transport.
     *
     * @var SMSTransportContract
     */
    protected $transport;


    /**
     * SendinblueTemplate constructor.
     *
     * @param SMSTransportContract $transport
     */
    public function __construct(SMSTransportContract $transport)
    {
        $this->transport = $transport;

        $this->reset();
    }


    /**
     * Reset message model.
     *
     * @return $this
     */
    public function reset()
    {
        $this->model = new SMSModel();

        return $this;
    }


    /**
     * Send SMS.
     *
     * @param null $callable
     * @return int
     */
    public function send($callable = null)
    {
        if (is_callable($callable))
        {
            $instance = app()->make(static::class);
            call_user_func($callable, $instance);
            $instance->send();
        }
        else
            return $this->transport->send($this);
    }



    /**
     * Set destination.
     *
     * @param string $mobile
     * @return $this
     */
    public function to($mobile)
    {
        $this->model->recipient = $mobile;

        return $this;
    }


    /**
     * Set sender name.
     *
     * @param $sender
     * @return $this
     */
    public function sender($sender)
    {
        $this->model->sender = $sender;

        return $this;
    }


    /**
     * Set message content.
     *
     * @param $content
     * @return $this
     */
    public function message($content)
    {
        $this->model->content = $content;

        return $this;
    }


    /**
     * Set SMS type.
     *
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->model->type = $type;

        return $this;
    }


    /**
     * Set tag.
     *
     * @param $tag
     * @return $this
     */
    public function tag($tag)
    {
        $this->model->tag = $tag;

        return $this;
    }


    /**
     * Set webhook URL.
     *
     * @param $web_url
     * @return $this
     */
    public function webUrl($web_url)
    {
        $this->model->webUrl = $web_url;

        return $this;
    }


    /**
     * Get model.
     *
     * @return SMSModel
     */
    public function getModel()
    {
        return $this->model;
    }

}