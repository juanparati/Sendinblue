<?php
namespace Juanparati\Sendinblue\Tests;

use PHPUnit\Framework\TestCase;

class SendinblueTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $apiKey;

    protected $sinkRecipient;


    protected function setUp(): void
    {
        parent::setUp();

        putenv('MAIL_DRIVER=sendinblue.v3');
        $this->apiKey = env('SENDINBLUE_API_KEY');
        $this->sinkRecipient = env('SINK_RECIPIENT');
    }
}
