<?php


namespace Juanparati\Sendinblue;


/**
 * Class SendinblueSMSProps.
 *
 * @package Juanparati\Sendinblue
 */
final class SMSModel
{

    /**
     * Name of the sender.
     *
     * Only alphanumeric characters. No more than 11 characters.
     *
     * @var string
     */
    public $sender;


    /**
     * Mobile number to send SMS with the country code.
     *
     * @var string
     */
    public $recipient;


    /**
     * The SMS content.
     *
     * If more than 160 characters long, multiple text messages will be sent.
     *
     * @var string
     */
    public $content;


    /**
     * SMS type.
     *
     * @var string
     */
    public $type;


    /**
     * Message tag.
     *
     * @var string
     */
    public $tag;


    /**
     * Webhook to call for each event triggered by the message
     *
     * @var string
     */
    public $webUrl;

}