<?php

namespace Juanparati\Sendinblue;


use SendinBlue\Client\Configuration;
use GuzzleHttp\Client as HTTPClient;
use SendinBlue\Client\Model\ModelInterface;

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
    protected Configuration $config;


    /**
     * @var HTTPClient
     */
    protected HTTPClient $client;


    /**
     * Constructor.
     *
     * @param $config
     */
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
     * @param string $api
     * @return mixed
     */
    public function getApi(string $api): mixed
    {
        $api = 'SendinBlue\\Client\\Api\\' . $api;
        return new $api($this->client, $this->config);
    }

    /**
     * Return the Model.
     *
     * @param string $model
     * @param array $data
     * @return ModelInterface
     */
    public function getModel(string $model, array $data = []): ModelInterface
    {
        $api = 'SendinBlue\\Client\\Model\\' . $model;
        return new $api($data);
    }


    /**
     * Get current configuration.
     *
     * @return Configuration
     */
    public function getConfig(): Configuration
    {
        return $this->config;
    }


    /**
     * Get HTTP client.
     *
     * @return HTTPClient
     */
    public function getClient(): HTTPClient
    {
        return $this->client;
    }

}
