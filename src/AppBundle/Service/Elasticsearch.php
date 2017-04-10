<?php

namespace AppBundle\Service;

use Elasticsearch\ClientBuilder;

class Elasticsearch
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * Elasticsearch constructor.
     * @param $host
     * @param $port
     * @param $scheme
     * @param $user
     * @param $pass
     */
    public function __construct($host, $port, $scheme, $user, $pass)
    {
        $this->host = $host;
        $this->port = $port;
        $this->scheme = $scheme;
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function getClient()
    {
        return ClientBuilder::create()->setHosts([[
            'host' => $this->host,
            'port' => $this->port,
            'scheme' => $this->scheme,
            'user' => $this->user,
            'pass' => $this->pass
        ]])->build();
    }
}