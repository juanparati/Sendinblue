<?php

namespace Juanparati\Sendinblue;

use Illuminate\Support\Traits\Macroable;

use Juanparati\Sendinblue\Contracts\SMSTransport as SMSTransportContract;
use Juanparati\Sendinblue\Exceptions\SMSException;


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
    protected SMSModel $model;


    /**
     * Template transport.
     *
     * @var SMSTransportContract
     */
    protected SMSTransportContract $transport;


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
    public function reset(): static
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
    public function send($callable = null): int
    {
        if (is_callable($callable))
        {
            $instance = app()->make(static::class);
            call_user_func($callable, $instance);
            return $instance->send();
        }

        return $this->transport->send($this);
    }



    /**
     * Set destination.
     *
     * @param string $mobile
     * @return $this
     */
    public function to($mobile): static
    {
        $this->model->recipient = $mobile;

        return $this;
    }


    /**
     * Set sender name.
     *
     * @param $sender
     * @return $this
     * @throws SMSException
     */
    public function sender(string|int $sender): static
    {
        // Remove all kind of spaces.
        $sender = preg_replace('/\s+/', '', $sender);

        if (strlen($sender) > 15)
            throw new SMSException('Sender number length is higher than 15 characters.');

        if (!is_numeric($sender) && strlen($sender) > 11)
            throw new SMSException('Sender name length is higher than 11 characters.');

        if (preg_match('/[^a-zA-Z\d]/', $sender))
            throw new SMSException('Sender name should contains only alphanumeric characters.');

        $this->model->sender = $sender;

        return $this;
    }


    /**
     * Set message content.
     *
     * @param $content
     * @return $this
     */
    public function message($content): static
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
    public function type($type): static
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
    public function tag($tag): static
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
    public function webUrl($web_url): static
    {
        $this->model->webUrl = $web_url;

        return $this;
    }


    /**
     * Get model.
     *
     * @return SMSModel
     */
    public function getModel(): SMSModel
    {
        return $this->model;
    }

}