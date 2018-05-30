<?php
namespace Juanparati\Sendinblue\Contracts;

use Juanparati\Sendinblue\Client;
use Juanparati\Sendinblue\Template;

interface TemplateTransport
{

    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param Client $api_client
     */
    public function __construct(Client $api_client);


    /**
     * Send the message using the given mailer.
     *
     * @param  int $template_id
     * @param Template $message
     * @return string Message ID
     */
    public function send(int $template_id, Template $message) : string;

}