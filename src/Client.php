<?php

namespace Juanparati\Sendinblue;


use SendinBlue\Client\Api\SMTPApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client as HTTPClient;

/**
 * Class Client.
 *
 * Generate Sendinblue v3 ApiClient.
 *
 * @package Juanparati\Sendinblue
 */
class Client
{

    /**
     * @var Configuration
     */
    protected $config;


    protected $client;


    public function __construct($config)
    {
        $http_client_options = [];

        $this->config = new Configuration();

        if (!empty($config['key']))
            $this->config->setApiKey('api-key', $config['key']);

        if (!empty($config['host']))
            $this->config->setHost($config['host']);

        if (!empty($config['timeout']))
            $http_client_options['timeout'] = $config['timeout'];

        if (!empty($config['debug']))
            $this->config->setDebug(true);

        $this->client = new HTTPClient($http_client_options);
    }


    /**
     * Return the API client.
     *
     * @return array
     */
    public function getApi($api)
    {
        $api = 'SendinBlue\\Client\\Api\\' . $api;
        return new $api($this->client, $this->config);
    }


    /**
     * Get current configuration.
     *
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * Get HTTP client.
     *
     * @return HTTPClient
     */
    public function getClient()
    {
        return $this->client;
    }

}