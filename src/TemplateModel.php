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
    public $from = [];


    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    public $to = [];


    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    public $cc = [];


    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    public $bcc = [];


    /**
     * The "reply to" recipients of the message.
     *
     * @var array
     */
    public $replyTo = [];


    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;


    /**
     * The attachments for the message.
     *
     * @var array
     */
    public $attachments = [];


    /**
     * The URL attachments for the message.
     *
     * @var array
     */
    public $attachmentsURL = [];


    /**
     * Template attributes.
     *
     * @var array
     */
    public $attributes = [];


    /**
     * Tags.
     *
     * @var array
     */
    public $tags = [];


    /**
     * The callbacks for the message.
     *
     * @var array
     */
    public $callbacks = [];


}