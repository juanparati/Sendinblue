# Sendinblue v3 for Laravel

## What is it?

A Laravel package that provides transactional features like:

- Laravel native mail transport
- Transactional template transport
- Transactional SMS transport


## Installation

        composer require juanparati/sendinblue

For Laravel 5.5 it is required to register the service provider into the "config/app.php":

        Juanparati\Sendinblue\ServiceProvider::class,

For Laravel 5.6+ the service provider is automatically registered.


## Setup native mail transport

1. Add the following configuration snippet into the "config/services.php" file

         'sendinblue' => [        
                'v3'    => [
                    'key'   => '[your v3 api key]'                    
                ]
         ],

2. Change the mail driver to "sendinblue.v3" into the "config/mail.php" file or the ".env" file. Example:
        
         'driver' => env('MAIL_DRIVER', 'sendinblue.v3'),
         



## Usage

### Transactional mail transport

Just use the transactional e-mails using the [Laravel Mail facade](https://laravel.com/docs/5.6/mail#sending-mail).

Remember to setup the native mail transport if you want to use Sendinblue as Laravel mail transport.


### Transactional mail template transport

The transactional mail template transport allow to send templates as transactional e-mails using Sendinblue.

It works in a very similar way to the Laravel Mail facade but with some inherited limitations of the Sendinblue templates.

It is possible to register the mail template transport facade into the "config/app.php":

         'MailTemplate' => Juanparati\Sendinblue\Facades\Template::class,

Now it is possible to send templates in the following way:

        MailTemplate::to('user@example.net');           // Recipient
        MailTemplate::cc('user2@example.net');          // CC
        MailTemplate::bcc('user3@example.net');         // BCC
        MailTemplate::replyTo('boss@example.net');      // ReplyTo
        MailTemplate::attribute('name', 'Mr User');     // Replace %NAME% placeholder into the template 
        MailTemplate::attach('file.txt');               // Attach file
        MailTemplate::attachURL('http://www.example.com/file.txt'); // Attach file from URL
        MailTemplate::send(100);                        // Send template ID 100 and return message ID in case of success

It is possible the reset the template message using the "reset" method:

        MailTemplate::to('user@example.net');           // Recipient
        MailTemplate::cc('user5@example.net');          // Second recipient
        MailTemplate::attribute('type', 'Invoice');     // Replace %TYPE% placeholder
        MailTemplate::send(100);                        // Send template
        
        MailTemplate::to('user2@example.net');          // Another recipient
        MailTemplate::send(100);                        // Send template but attribute "type" and second recipient from previous e-mail is used
        
        MailTemplate::reset();                          // Reset message
        
        MailTemplate::to('user3@example.net');          
        MailTemplate::send(100);                        // Send template but previous attribute and second recipient is not used.
                

In is also possible enclose the mail message into a closure so it is not necessary to reset the message state using the "reset" method:

        MailTemplate::send(100, function ($message) {
            $message->to('user2@example.net');
            $message->attributes(['placeholder1' => 'one', 'placeholder2' => 'two']);
            ...
        });        


### Transactional SMS

The transactional SMS allow to send SMS using the Sendinblue SMS transport.

It is possible to register the SMS transport facade into the "config/app.php":

        'SMS' => Juanparati\Sendinblue\Facades\SMS::class,

Usage examples:

        SMS::sender('The Boss');        // Sender name
        SMS::to('45123123123');         // Mobile number with internal code (ES)
        SMS::message('Come to work!');  // SMS message
        SMS::tag('lazydev');            // Tag (Optional)
        SMS::webUrl('http://example.com/endpoint'); // Notification webhook (Optional);
        SMS::send();
        
Like the the transactional template transport, it is also possible reset the state using the "reset" method or just using a closure:

        SMS::send(function($sms) {
            $sms->to('45123123123');
            $sms->sender('Mr Foo');
            $sms->message('Hello Mr Bar');
            ...
        });
        

        