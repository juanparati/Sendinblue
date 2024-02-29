# Sendinblue v3 for Laravel

## What is it?

A Laravel package that provides transactional features like:

- Laravel native mail transport
- Transactional template transport
- Transactional SMS transport

## Note

Sendinblue changed the name to Brevo.
For new Laravel 10+ installations please use the [Brevo Suite library for Laravel](https://github.com/juanparati/BrevoSuite).


## Installation

For Laravel 10.x

      composer require juanparati/sendinblue "^10.0"

For Laravel 9.x

      composer require juanparati/sendinblue "^9.0"

For Laravel 8.x

      composer require juanparati/sendinblue "^8.0"

For Laravel 7.x:

      composer require juanparati/sendinblue "^4.0"


For Laravel 6.x:

      composer require juanparati/sendinblue "^3.0"

For Laravel 5.5 to 5.8:

      composer require juanparati/sendinblue "^2.4"

For Laravel 5.5 and below it's required to register the service provider into the "config/app.php":

        Juanparati\Sendinblue\ServiceProvider::class,

For Laravel 5.6+ the service provider is automatically registered.


## <a name="setup-native-mail-transport"></a> Setup native mail transport in Laravel 7+

1. Add the following configuration snippet into the "config/services.php" file

         'sendinblue' => [        
                'v3'    => [
                    'key'   => '[your v3 api key]'                    
                ]
         ],

2. Change the mail driver to "sendinblue.v3" into the "config/mail.php" file or the ".env" file (Remember that ".env" values will overwrite the config values). Example:
        
         'driver' => env('MAIL_MAILER', 'sendinblue'),

         'mailers' => [
                 // ...
                 'sendinblue' => [
                        'transport' => 'sendinblue.v3'
                 ]
                 // ...
         ];

3. Add the following configuration snippet into the "config/services.php" file

         'sendinblue' => [        
                'v3'    => [
                    'key'   => '[your v3 api key]'                    
                ]
         ],


## <a name="setup-native-mail-transport"></a> Setup native mail transport in Laravel 5.x/6.x

1. Add the following configuration snippet into the "config/services.php" file

         'sendinblue' => [        
                'v3'    => [
                    'key'   => '[your v3 api key]'                    
                ]
         ],

2. Change the mail driver to "sendinblue.v3" into the "config/mail.php" file or the ".env" (Remember that ".env" values will overwrite the config values) file. Example:
        
         'driver' => env('MAIL_DRIVER', 'sendinblue.v3'),
         



## Usage

### Transactional mail transport

Just use the transactional e-mails using the [Laravel Mail facade](https://laravel.com/docs/8.x/mail#sending-mail).


As soon that Sendinblue was configured as native mail transport you can use the following code in order to test it:

    // Paste this code inside "artisan tinker" console.
    Mail::raw('Test email', function ($mes) { 
        $mes->to('[youremail@example.tld]'); 
        $mes->subject('Test'); 
    });

        


### Transactional mail template transport

The transactional mail template transport allow to send templates as transactional e-mails using Sendinblue.

It's possible to register the mail template transport facade into the "config/app.php":

         'MailTemplate' => Juanparati\Sendinblue\Facades\Template::class,

Now it's possible to send templates in the following way:

        MailTemplate::to('user@example.net');           // Recipient
        MailTemplate::cc('user2@example.net');          // CC
        MailTemplate::bcc('user3@example.net');         // BCC
        MailTemplate::replyTo('boss@example.net');      // ReplyTo
        MailTemplate::attribute('NAME', 'Mr User');     // Replace %NAME% placeholder into the template 
        MailTemplate::attach('file.txt');               // Attach file
        MailTemplate::attachURL('http://www.example.com/file.txt'); // Attach file from URL
        MailTemplate::send(100);                        // Send template ID 100 and return message ID in case of success

It's possible the reset the template message using the "reset" method:

        MailTemplate::to('user@example.net');           // Recipient
        MailTemplate::cc('user5@example.net');          // Second recipient
        MailTemplate::attribute('TYPE', 'Invoice');     // Replace %TYPE% placeholder
        MailTemplate::send(100);                        // Send template
        
        MailTemplate::to('user2@example.net');          // Another recipient
        MailTemplate::send(100);                        // Send template but attribute "type" and second recipient from previous e-mail is used
        
        MailTemplate::reset();                          // Reset message
        
        MailTemplate::to('user3@example.net');          
        MailTemplate::send(100);                        // Send template but previous attribute and second recipient is not used.
                

It's also possible enclose the mail message into a closure so the call to the "reset" method is not neccesary:

        MailTemplate::send(100, function ($message) {
            $message->to('user2@example.net');
            
            // Note: Your template should contains the placeholder attributes surrounded by "%" symbol.
            // @see: https://help.sendinblue.com/hc/en-us/articles/209557065-Customize-transactional-email-templates
            $message->attributes(['placeholder1' => 'one', 'placeholder2' => 'two']);
            ...
        });        


### Transactional SMS

The transactional SMS allow to send SMS using the Sendinblue SMS transport.

I's possible to register the SMS transport facade into the "config/app.php":

        'SMS' => Juanparati\Sendinblue\Facades\SMS::class,

Usage examples:

        SMS::sender('TheBoss');         // Sender name (Spaces and symbols are not allowed)
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
        

### Laravel notifications

The following classes are provided as message builder for Laravel notifications:

- TemplateMessage
- SMSMessage


### API Client

By default this library uses the official [Sendinblue PHP library](https://github.com/sendinblue/APIv3-php-library).

In order to interact with the official library it is posible to inject the custom APIs in the following way:

        // Obtain APIClient
        $api_client = app()->make(\Juanparati\Sendinblue\Client::class);
        
        // Use the APIClient with the Sendinblue ContactsAPI
        $contacts_api = $api_client->getApi('ContactsApi');
        
        // Retrieve the first 10 folders
        $folders = $contacts_api->getFolders(10, 0);  

Another example using Sendinblue models:

        $apiClient = app()->make(\Juanparati\Sendinblue\Client::class);
        $contactsApi = $apiClient->getApi('ContactsApi');

        // Use CreateContact model
        $contact = $apiClient->getModel('CreateContact', ['email' => 'test@example.net', 'attributes' => ['TYPE' => 4, 'NOM' => 'test', 'PRENOM' => 'test'], 'listIds' => [22]]);

        try {
                $contactsApi->createContact($contact);
        }
        catch(\Exception $e){
                dd($e->getMessage());
        }

See the [Sendinblue v3 APIs](https://github.com/sendinblue/APIv3-php-library) for more details.    


### Supported by

This project was made possible by [Matchbanker.no](https://matchbanker.no/).
