<?php
namespace Juanparati\Sendinblue\Tests\Test;

use Juanparati\Sendinblue\Client;
use Juanparati\Sendinblue\Tests\SendinblueTestCase;
use Juanparati\Sendinblue\Transport as SendinblueTransport;
use Illuminate\Mail\Message;


class SendinblueTransportTest extends SendinblueTestCase
{



    /**
     * Test normal transaction e-mail.
     *
     * @throws \Juanparati\Sendinblue\Exceptions\TransportException
     */
    public function testSend()
    {
        $message = new Message($this->getMessage());

        $message->from('test@example.net', 'ExampleSender')
            ->to($this->sinkRecipient, 'Destination Recipient');

        /*
        $res = $this->transport->send($message->getSwiftMessage());
        */

        $this->assertEquals(1, $res);
    }



    /**
     * Generate a test message.
     *
     * @return Swift_Message
     */
    protected function getMessage()
    {
        //return new \Swift_Message('Test subject', 'Test body.');
    }
}
