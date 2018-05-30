<?php

namespace Juanparati\Sendinblue;


use Illuminate\Mail\Transport\Transport as MailTransport;


use Illuminate\Support\Facades\Log;
use Swift_Attachment;
use Swift_Mime_SimpleMessage;
use Swift_MimePart;

use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailAttachment;
use SendinBlue\Client\Model\SendSmtpEmailBcc;
use SendinBlue\Client\Model\SendSmtpEmailCc;
use SendinBlue\Client\Model\SendSmtpEmailReplyTo;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;

use Juanparati\Sendinblue\Exceptions\TransportException;


/**
 * Class SendinBlueTransport.
 *
 * Basic transaction e-mail mail driver for Laravel.
 *
 * @package Juanparati\Sendinblue
 */
class Transport extends MailTransport
{

    /**
     * SendinBlue SMTP instance.
     *
     * @var \SendinBlue\Client\Api\SMTPApi
     */
    protected $instance;


    /**
     * SendinBlueTransport constructor.
     *
     * @param Client $api_client
     */
    public function __construct(Client $api_client)
    {
        $this->instance = $api_client->getApi('SMTPApi');
    }


    /**
     * Send e-mail.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @param null $failedRecipients
     * @return int
     * @throws TransportException
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $data = $this->mapMessage($message);

        try
        {
            $response = $this->instance->sendTransacEmail($data);
        }
        catch (\Exception $e)
        {
            throw new TransportException($e->getMessage());
        }


        if (empty($response->getMessageId()))
            throw new TransportException('Unable to send e-mail, due to unknown error');


        Log::debug('Sent Sendinblue message', ['messageId' => $response->getMessageId()]);


        return 1;
    }


    /**
     * Transforms Swift_Message into SendinBlue SendSmtpEmail.
     *
     * @param  Swift_Mime_SimpleMessage $message
     * @return SendSmtpEmail
     * @throws TransportException
     */
    protected function mapMessage(Swift_Mime_SimpleMessage $message)
    {
        $mailer = new SendSmtpEmail();

        // Set receivers
        if ($to = $message->getTo())
            $mailer->setTo(static::collectEmailNamePair($to, SendSmtpEmailTo::class));
        else
            throw new TransportException('Destination (To) recipient is required', 100);


        // Set subject
        if ($subject = $message->getSubject())
            $mailer->setSubject($subject);
        else
        {
            // Because subject is required by Sendinblue let's add a default one
            $mailer->setSubject('No subject');
        }


        // Set sender
        if ($from = $message->getFrom())
            $mailer->setSender(static::collectEmailNamePair($from, SendSmtpEmailSender::class )[0]);


        // Set CC
        if ($cc = $message->getCc())
            $mailer->setCc(static::collectEmailNamePair($cc, SendSmtpEmailCc::class));


        // Set BCC
        if ($bcc = $message->getBcc())
            $mailer->setBcc(static::collectEmailNamePair($bcc, SendSmtpEmailBcc::class));


        // Set ReplyTo
        if ($reply_to = $message->getReplyTo())
            $mailer->setReplyTo(new SendSmtpEmailReplyTo(['email' => array_keys($reply_to)[0]]));
        else
        {
            // A Reply-to is always required.
            //$mailer->setReplyTo(new SendSmtpEmailReplyTo(['email' => array_keys($from)[0]]));
        }


        // Set content
        $plain_text = '';
        $html_text  = '';

        if ($message->getContentType() == 'text/plain')
            $plain_text = $message->getBody();
        else
            $html_text  = $mailer->setHtmlContent($message->getBody());


        // Set mime parts
        $children = $message->getChildren();

        foreach ($children as $child) {
            if ($child instanceof Swift_MimePart && $child->getContentType() == 'text/plain') {
                $plain_text = $child->getBody();
            }
        }


        // Add attachments
        $attachment = [];

        foreach ($children as $child) {
            if ($child instanceof Swift_Attachment) {
                $filename = $child->getFilename();

                if (!AttachExt::isAllowed($filename))
                    throw new TransportException('File extension not allowed for ' . $filename, 101);

                $content = chunk_split(base64_encode($child->getBody()));
                $attachment[] = new SendSmtpEmailAttachment(['content' => $content, 'name' => $filename]);
            }
        }


        if (count($attachment))
            $mailer->setAttachment($attachment);


        // Sanitize and set text content
        if (!empty($plain_text)) {

            // Sendinblue v3 requires always a htmlcontent
            $mailer->setHtmlContent(empty($html_text) ? $plain_text : $html_text->getHtmlContent());

            $plain_text = strip_tags($plain_text);
            $mailer->setTextContent($plain_text);
        }


        return $mailer;
    }


    /**
     * Convert Swift address pair into SendinBlue models.
     *
     * @param array $source
     * @param string $type
     * @return array
     */
    public static function collectEmailNamePair(array $source, string $type) : array
    {
        $values = [];

        foreach ($source as $email => $name)
            $values[] = new $type(['name' => $name, 'email' => $email]);

        return $values;
    }

}
