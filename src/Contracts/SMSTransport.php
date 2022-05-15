<?php
namespace Juanparati\Sendinblue\Contracts;

use Juanparati\Sendinblue\Client;
use Juanparati\Sendinblue\SMS;


interface SMSTransport
{

    /**
     * SendinblueSMSTransport constructor.
     *
     * @param Client $apiClient
     */
    public function __construct(Client $apiClient);


    /**
     * Send the SMS using the given message.
     *
     * @param SMS $message
     * @return string Message ID
     */
    public function send(SMS $message) : string;

}