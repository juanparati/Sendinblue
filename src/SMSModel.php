<?php


namespace Juanparati\Sendinblue;


/**
 * Class SendinblueSMSProps.
 *
 * @see: https://developers.sendinblue.com/reference/sendtransacsms
 * @package Juanparati\Sendinblue
 */
final class SMSModel
{

    /**
     * Name of the sender.
     *
     * Only alphanumeric characters. No more than 15 numbers or 11 characters.
     *
     * @var string|int
     */
    public string|int $sender = '';


    /**
     * Mobile number to send SMS with the country code.
     *
     * @var string
     */
    public string $recipient = '';


    /**
     * The SMS content.
     *
     * If more than 160 characters long, multiple text messages will be sent.
     *
     * @var string
     */
    public string $content = '';


    /**
     * SMS type.
     *
     * @var string
     */
    public string $type = 'transactional';


    /**
     * Message tag.
     *
     * @var string|null
     */
    public ?string $tag;


    /**
     * Webhook to call for each event triggered by the message
     *
     * @var string|null
     */
    public ?string $webUrl;

}