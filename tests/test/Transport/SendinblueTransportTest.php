<?php
namespace Juanparati\Sendinblue\Tests\Test;

use Juanparati\Sendinblue\Client;
use Juanparati\Sendinblue\Tests\SendinblueTestCase;
use Juanparati\Sendinblue\Transport as SendinblueTransport;
use Illuminate\Mail\Message;


class SendinblueTransportTest extends SendinblueTestCase
{

    /**
     * @var SendinblueTransport
     */
    protected $transport;


    /**
     * Setup before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $client = new Client([
            'key'   => $this->api_key,
            'debug' => true
        ]);

        $this->transport = new SendinblueTransport($client);
    }


    /**
     * Test normal transaction e-mail.
     *
     * @throws \Juanparati\Sendinblue\Exceptions\TransportException
     */
    public function testSend()
    {
        $message = new Message($this->getMessage());

        $message->from('test@example.net', 'ExampleSender')
            ->to($this->sink_recipient, 'Destination Recipient');

        $res = $this->transport->send($message->getSwiftMessage());

        $this->assertEquals(1, $res);
    }



    /**
     * Generate a test message.
     *
     * @return Swift_Message
     */
    protected function getMessage()
    {
        return new \Swift_Message('Test subject', 'Test body.');
    }
}
