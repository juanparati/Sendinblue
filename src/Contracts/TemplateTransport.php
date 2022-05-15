<?php
namespace Juanparati\Sendinblue\Contracts;

use Juanparati\Sendinblue\Client;
use Juanparati\Sendinblue\Template;

interface TemplateTransport
{

    /**
     * SendinblueTemplateTransport constructor.
     *
     * @param Client $apiClient
     */
    public function __construct(Client $apiClient);


    /**
     * Send the message using the given mailer.
     *
     * @param int $templateId
     * @param Template $message
     * @return string Message ID
     */
    public function send(int $templateId, Template $message) : string;

}