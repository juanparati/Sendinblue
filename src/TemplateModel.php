<?php


namespace Juanparati\Sendinblue;


/**
 * Class SendinblueTemplateProps.
 *
 * @package Juanparati\Sendinblue
 */
final class TemplateModel
{

    /**
     * The person the message is from.
     *
     * @var array
     */
    public array $from = [];


    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    public array $to = [];


    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    public array $cc = [];


    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    public array $bcc = [];


    /**
     * The "reply to" recipients of the message.
     *
     * @var array
     */
    public array $replyTo = [];


    /**
     * The subject of the message.
     *
     * @var string
     */
    public string $subject;


    /**
     * The attachments for the message.
     *
     * @var array
     */
    public array $attachments = [];


    /**
     * The URL attachments for the message.
     *
     * @var array
     */
    public array $attachmentsURL = [];


    /**
     * Template attributes.
     *
     * @var array
     */
    public array $attributes = [];


    /**
     * Tags.
     *
     * @var array
     */
    public array $tags = [];


    /**
     * The callbacks for the message.
     *
     * @var array
     */
    public array $callbacks = [];


}