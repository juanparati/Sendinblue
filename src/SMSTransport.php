<?php

namespace Juanparati\Sendinblue;


use Illuminate\Support\Facades\Log;

use SendinBlue\Client\Api\TransactionalSMSApi;
use SendinBlue\Client\ApiClient;

use Juanparati\Sendinblue\Exceptions\TransportException;
use Juanparati\Sendinblue\Contracts\SMSTransport as SendinblueSMSTransportContract;
use SendinBlue\Client\Model\SendTransacSms;


/**
 * Class SendinblueTemplateTransport.
 *
 * Email transport used for Sendinblue templates.
 *
 * @package Juanparati\Sendinblue
 */
class SMSTransport implements SendinblueSMSTransportContract
{

    /**
     * SendinBlue SMS instance.
     *
     * @var \SendinBlue\Client\Api\TransactionalSMSApi
     */
    protected $instance;


    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param Client $api_client
     */
    public function __construct(Client $api_client)
    {
        $this->instance = $api_client->getApi('TransactionalSMSApi');
    }


    /**
     * Send the SMS using the given sms message.
     *
     * @return string Message ID
     * @throws TransportException
     */
    public function send(SMS $message) : string
    {

        $data = $this->mapMessage($message->getModel());

        try
        {
            $response = $this->instance->sendTransacSms($data);
        }
        catch (\Exception $e)
        {
            throw new TransportException($e->getMessage());
        }


        $message_id = $response->getMessageId();

        if (empty($message_id))
            throw new TransportException('Unable to send SMS, due to unknown error');


        // Log::debug('Sent Sendinblue SMS', ['messageId' => $message_id]);


        return $message_id;
    }


    /**
     * Transforms Model into SendTransacSms.
     *
     * @param SMSModel $message
     * @return SendTransacSms
     * @throws TransportException
     */
    protected function mapMessage(SMSModel $message)
    {

        $sms = new SendTransacSms();


        // Set recipient
        if (!$message->recipient)
            throw new TransportException('Destination recipient is required', 100);

        $sms->setRecipient($message->recipient);


        // Set content
        $content = trim($message->content);

        if (!$content)
            throw new TransportException('Message content is missing', 111);

        $sms->setContent($content);


        // Set sender
        if (!$message->sender)
            throw new TransportException('Sender name is required', 1111);

        $sms->setSender(substr($message->sender, 0, 11));


        // Set type
        if ($message->type)
            $sms->setType($message->type);


        // Set tag
        if ($message->tag)
            $sms->setTag($message->tag);


        // Set webhook
        if ($message->webUrl)
            $sms->setWebUrl($message->webUrl);

        return $sms;
    }


}
