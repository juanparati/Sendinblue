<?php

namespace Juanparati\Sendinblue;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

use Juanparati\Sendinblue\Contracts\TemplateTransport as TemplateTransportContract;


/**
 * Class SendinblueTemplate.
 *
 * Sendinblue Template message.
 *
 * @package Juanparati\Sendinblue
 */
class Template
{

    use Macroable;


    /**
     * Default placeholder quote symbol.
     *
     * @see https://help.sendinblue.com/hc/en-us/articles/209557065-Customize-transactional-email-templates
     */
    const PLACEHOLDER_QUOTE = '%';


    /**
     * Template model.
     *
     * @var TemplateModel
     */
    protected $model;


    /**
     * SendinBlue SMTP instance.
     *
     * @var \SendinBlue\Client\Api\SMTPApi
     */
    protected $instance;


    /**
     * Template transport.
     *
     * @var TemplateTransport
     */
    protected $transport;


    /**
     * SendinblueTemplate constructor.
     *
     * @param Client $api_client
     * @param TemplateTransportContract $transport
     */
    public function __construct(Client $api_client, TemplateTransportContract $transport)
    {
        $this->instance  = $api_client->getApi('SMTPApi');
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
        $this->model = new TemplateModel();

        return $this;
    }


    /**
     * Send the message using the given mailer.
     *
     * @param int $template_id
     * @param \Closure|string $callable
     * @return string The Message ID
     * @throws Exceptions\TransportException
     */
    public function send($template_id, $callable = null) : string
    {
        if (is_callable($callable))
        {
            $instance = app()->make(static::class);
            call_user_func($callable, $instance);
            $instance->send($template_id);
        }
        else
            return $this->transport->send($template_id, $this);
    }


    /**
     * Build the view for the message.
     *
     * @return array|string
     */

    public function buildView($template_id)
    {
        $template = $this->instance->getSmtpTemplate($template_id);

        // Replace attributes placeholders
        foreach ($this->model->attributes as $placeholder => $value)
        {
            $template = str_replace(
                static::PLACEHOLDER_QUOTE . $placeholder . static::PLACEHOLDER_QUOTE,
                $value,
                $template);
        }


        return $template['htmlContent'];
    }


    /**
     * Set the sender of the message.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function from($address, $name = null)
    {
        return $this->setAddress($address, $name, 'from');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return bool
     */
    public function hasFrom($address, $name = null)
    {
        return $this->hasRecipient($address, $name, 'from');
    }

    /**
     * Set the recipients of the message.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function to($address, $name = null)
    {
        return $this->setAddress($address, $name, 'to');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return bool
     */
    public function hasTo($address, $name = null)
    {
        return $this->hasRecipient($address, $name, 'to');
    }

    /**
     * Set the recipients of the message.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function cc($address, $name = null)
    {
        return $this->setAddress($address, $name, 'cc');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return bool
     */
    public function hasCc($address, $name = null)
    {
        return $this->hasRecipient($address, $name, 'cc');
    }

    /**
     * Set the recipients of the message.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function bcc($address, $name = null)
    {
        return $this->setAddress($address, $name, 'bcc');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return bool
     */
    public function hasBcc($address, $name = null)
    {
        return $this->hasRecipient($address, $name, 'bcc');
    }

    /**
     * Set the "reply to" address of the message.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return $this
     */
    public function replyTo($address, $name = null)
    {
        return $this->setAddress($address, $name, 'replyTo');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return bool
     */
    public function hasReplyTo($address, $name = null)
    {
        return $this->hasRecipient($address, $name, 'replyTo');
    }

    /**
     * Set the recipients of the message.
     *
     * All recipients are stored internally as [['name' => ?, 'address' => ?]]
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @param  string  $property
     * @return $this
     */
    protected function setAddress($address, $name = null, $property = 'to')
    {

    	if ($property === 'replyTo') {

			$this->model->{$property} = [
				'name'    => $name ?? null,
				'address' => $address,
			];

			return $this;
		}


		foreach ($this->addressesToArray($address, $name) as $recipient)
		{
			$recipient = $this->normalizeRecipient($recipient);

			$this->model->{$property}[] = [
				'name'    => $recipient->name ?? null,
				'address' => $recipient->email,
			];

		}

        return $this;
    }

    /**
     * Convert the given recipient arguments to an array.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @return array
     */
    protected function addressesToArray($address, $name)
    {

        if (! is_array($address) && ! $address instanceof Collection) {
            $address = is_string($name) ? [['name' => $name, 'email' => $address]] : [$address];
        }

        return $address;
    }

    /**
     * Convert the given recipient into an object.
     *
     * @param  mixed  $recipient
     * @return object
     */
    protected function normalizeRecipient($recipient)
    {
        if (is_array($recipient)) {
            return (object) $recipient;
        } elseif (is_string($recipient)) {
            return (object) ['email' => $recipient];
        }

        return $recipient;
    }

    /**
     * Determine if the given recipient is set on the mailable.
     *
     * @param  object|array|string  $address
     * @param  string|null  $name
     * @param  string  $property
     * @return bool
     */
    protected function hasRecipient($address, $name = null, $property = 'to')
    {
        $expected = $this->normalizeRecipient(
            $this->addressesToArray($address, $name)[0]
        );

        $expected = [
            'name' => $expected->name ?? null,
            'address' => $expected->email,
        ];

        return collect($this->model->{$property})->contains(function ($actual) use ($expected) {
            if (! isset($expected['name'])) {
                return $actual['address'] == $expected['address'];
            }

            return $actual == $expected;
        });
    }


    /**
     * Attach a file to the message.
     *
     * @param  string  $file
     * @param  array  $options
     * @return $this
     */
    public function attach($file, array $options = [])
    {
        $this->model->attachments[] = compact('file', 'options');

        return $this;
    }


    /**
     * Set template attribute.
     *
     * @see https://help.sendinblue.com/hc/en-us/articles/209557065-Customize-transactional-email-templates
     * @param $name
     * @param $value
     * @return $this
     */
    public function attribute($name, $value)
    {
        $this->model->attributes[$name] = $value;

        return $this;
    }


    /**
     * Set template attributes as array.
     *
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes)
    {
        $this->model->attributes = $this->model->attributes + $attributes;

        return $this;
    }


    /**
     * Add tag(s).
     *
     * @param mixed ...$tag
     * @return $this
     */
    public function tag(...$tag)
    {
        $this->model->tags = array_merge($this->model->tags, $tag);

        return $this;
    }


    /**
     * Attach documents by URL.
     *
     * @param array $file
     * @return $this
     */
    public function attachURL($file)
    {
        $this->model->attachmentURL = $file;

        return $this;
    }


    /**
     * Get model.
     *
     * @return TemplateModel
     */
    public function getModel()
    {
        return $this->model;
    }

}
