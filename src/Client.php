<?php

namespace Juanparati\Sendinblue;


use SendinBlue\Client\ApiClient;
use SendinBlue\Client\Configuration;

class Client
{

    /**
     * @var Configuration
     */
    protected $config;


    public function __construct($config)
    {
        $this->config = new Configuration();

        if (!empty($config['key']))
            $this->config->setApiKey('api-key', $config['key']);

        if (!empty($config['host']))
            $this->config->setHost($config['host']);

        if (!empty($config['timeout']))
            $this->config->setCurlConnectTimeout($config['timeout']);

        if (!empty($config['debug']))
            $this->config->setDebug(true);

    }


    /**
     * Return the API client.
     *
     * @return ApiClient
     */
    public function getApiClient()
    {
        return new ApiClient($this->config);
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

}