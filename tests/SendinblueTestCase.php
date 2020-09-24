<?php
namespace Juanparati\Sendinblue\Tests;

use PHPUnit\Framework\TestCase;

class SendinblueTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $api_key;

    protected $sink_recipient;


    protected function setUp(): void
    {
        parent::setUp();

        putenv('MAIL_DRIVER=sendinblue.v3');
        $this->api_key = env('SENDINBLUE_API_KEY');
        $this->sink_recipient = env('SINK_RECIPIENT');
    }
}
