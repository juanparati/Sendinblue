<?php
namespace Juanparati\Sendinblue\Contracts;

use Juanparati\Sendinblue\Template;
use SendinBlue\Client\ApiClient;

interface TemplateTransport
{

    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param ApiClient $api_client
     */
    public function __construct(ApiClient $api_client);


    /**
     * Send the message using the given mailer.
     *
     * @param  int $template_id
     * @return string Message ID
     */
    public function send($template_id, Template $message) : string;

}