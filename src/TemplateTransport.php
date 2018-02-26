<?php

namespace Juanparati\Sendinblue;


use Illuminate\Support\Facades\Log;

use SendinBlue\Client\Api\SMTPApi;
use SendinBlue\Client\ApiClient;
use SendinBlue\Client\Model\SendEmail;
use SendinBlue\Client\Model\SendEmailAttachment;

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
     * @var \SendinBlue\Client\Api\SMTPApi
     */
    protected $instance;


    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param ApiClient $api_client
     */
    public function __construct(ApiClient $api_client)
    {
        $this->instance = new SMTPApi($api_client);
    }


    /**
     * Send the message using the given mailer.
     *
     * @param  int $template_id
     * @return string Message ID
     * @throws TransportException
     */
    public function send($template_id, Template $message) : string
    {

        $data = $this->mapMessage($message->getModel());

        try
        {
            $response = $this->instance->sendTemplate($template_id, $data);
        }
        catch (\Exception $e)
        {
            throw new TransportException($e->getMessage());
        }


        $message_id = $response->getMessageId();

        if (empty($message_id))
            throw new TransportException('Unable to send e-mail template, due to unknown error');


        Log::debug('Sent Sendinblue template message', ['messageId' => $message_id]);


        return $message_id;
    }


    /**
     * Transforms Swift_Message into SendinBlue SendSmtpEmail.
     *
     * @param TemplateModel $message
     * @return SendEmail
     * @throws TransportException
     */
    protected function mapMessage(TemplateModel $message)
    {

        $mailer = new SendEmail();

        // Set receivers
        if ($message->to)
            $mailer->setEmailTo(collect($message->to)->pluck('address')->all());
        else
            throw new TransportException('Destination (To) recipient is required', 100);


        // Set CC
        if ($message->cc)
            $mailer->setEmailCc(collect($message->cc)->pluck('address')->all());


        // Set BCC
        if ($message->bcc)
            $mailer->setEmailBcc(collect($message->bcc)->pluck('address')->all());


        // Set ReplyTo
        if ($message->replyTo)
            $mailer->setReplyTo(collect($message->replyTo)->pluck('address')->first());


        // Add attachments
        $attachment = [];

        foreach ($message->attachments as $child) {

            $filename = $filepath = $child['file'];

            if (!empty($child['options']['as']))
                $filename = $child['options']['as'];

            if (!AttachExt::isAllowed($filename))
                throw new TransportException('File extension not allowed for ' . $filename, 101);

            $content = chunk_split(base64_encode(file_get_contents($filepath)));
            $attachment[] = new SendEmailAttachment(['content' => $content, 'name' => $filename]);
        }

        if (count($attachment))
            $mailer->setAttachment($attachment);


        // Add URL attachments
        foreach ($message->attachmentsURL as $attachmentURL)
            $mailer->setAttachmentUrl($attachmentURL);


        // Set attributes
        if ($message->attributes)
            $mailer->setAttributes($message->attributes);

        return $mailer;
    }


}