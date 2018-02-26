<?php
namespace Juanparati\Sendinblue\Contracts;

use Juanparati\Sendinblue\SMS;
use SendinBlue\Client\ApiClient;

interface SMSTransport
{

    /**
     * SendinblueSMSTransport constructor.
     *
     * @param ApiClient $api_client
     */
    public function __construct(ApiClient $api_client);


    /**
     * Send the SMS using the given message.
     *
     * @param SMS $message
     * @return string Message ID
     */
    public function send(SMS $message) : string;

}