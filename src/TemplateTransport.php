<?php

namespace Juanparati\Sendinblue;


use Illuminate\Support\Facades\Log;

use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailAttachment;
use SendinBlue\Client\Model\SendSmtpEmailBcc;
use SendinBlue\Client\Model\SendSmtpEmailCc;
use SendinBlue\Client\Model\SendSmtpEmailReplyTo;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;

use Juanparati\Sendinblue\Exceptions\TransportException;
use Juanparati\Sendinblue\Contracts\TemplateTransport as SendinblueTemplateTransportContract;


/**
 * Class SendinblueTemplateTransport.
 * Email transport used for Sendinblue templates.
 *
 * @package Juanparati\Sendinblue
 */
class TemplateTransport implements SendinblueTemplateTransportContract
{

    /**
     * SendinBlue SMTP instance.
     *
     * @var \SendinBlue\Client\Api\TransactionalEmailsApi
     */
    protected $instance;


    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param Client $api_client
     */
    public function __construct(Client $api_client)
    {
        $this->instance = $api_client->getApi('TransactionalEmailsApi');
    }


    /**
     * Send the message using the given mailer.
     *
     * @param  int $template_id
     * @param Template $message
     * @return string Message ID
     * @throws TransportException
     */
    public function send(int $template_id, Template $message) : string
    {

        $data = $this->mapMessage($message->getModel());
        $data->setTemplateId($template_id);

        try
        {
            $response = $this->instance->sendTransacEmail($data);
        }
        catch (\Exception $e)
        {
            throw new TransportException($e->getMessage());
        }

        $message_id = $response->getMessageId();

        if (empty($message_id))
            throw new TransportException('Unable to send e-mail template, due to unknown error');


        // Log::debug('Sent Sendinblue template message', ['messageId' => $message_id]);


        return $message_id;
    }


    /**
     * Transforms Swift_Message into SendinBlue SendSmtpEmail.
     *
     * @param TemplateModel $message
     * @return SendSmtpEmail
     * @throws TransportException
     */
    protected function mapMessage(TemplateModel $message)
    {

        $mailer = new SendSmtpEmail();

        if ($message->to)
            $mailer->setTo(static::collectEmailNamePair($message->to, SendSmtpEmailTo::class));
        else
            throw new TransportException('Destination (To) recipient is required', 100);

        if ($message->from)
            $mailer->setSender(new SendSmtpEmailSender(['email' => $message->from['address'], 'name' => $message->from['name']]));

        // Set CC
        if ($message->cc)
            $mailer->setCc(static::collectEmailNamePair($message->cc, SendSmtpEmailCc::class));


        // Set BCC
        if ($message->bcc)
            $mailer->setBcc(static::collectEmailNamePair($message->bcc, SendSmtpEmailBcc::class));


        // Set ReplyTo
        if ($message->replyTo)
            $mailer->setReplyTo(new SendSmtpEmailReplyTo(['email' => $message->replyTo['address'], 'name' => $message->replyTo['name']]));


        // Add attachments
        $attachment = [];

        foreach ($message->attachments as $child) {

            $filename = $filepath = $child['file'];

            if (!empty($child['options']['as']))
                $filename = $child['options']['as'];

            if (!AttachExt::isAllowed($filename))
                throw new TransportException('File extension not allowed for ' . $filename, 101);

            $content = chunk_split(base64_encode(file_get_contents($filepath)));
            $attachment[] = new SendSmtpEmailAttachment(['content' => $content, 'name' => $filename]);
        }


        // Add URL attachments
        foreach ($message->attachmentsURL as $attachmentURL)
            $attachment[] = new SendSmtpEmailAttachment(['url' => $attachmentURL]);


        // Enclose all the attachments
        if (count($attachment))
            $mailer->setAttachment($attachment);


        // Set attributes
        if ($message->attributes)
            $mailer->setParams($message->attributes);


        return $mailer;
    }


    /**
     * Convert address pair into SendinBlue models.
     *
     * @param array $source
     * @param string $type
     * @return array
     */
    public static function collectEmailNamePair(array $source, string $type) : array
    {
        $values = [];

        foreach ($source as $address)
            $values[] = new $type(['name' => $address['name'], 'email' => $address['address']]);

        return $values;
    }


}
