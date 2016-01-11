<?php

namespace Alexboo\WebSocketHandler\Interfaces;

use Alexboo\WebSocketHandler\Client;

interface HandlerFactoryInterface
{
    /**
     * Get handler for command
     * @param Client $client
     * @param $command
     * @return Handler
     */
    public function getHandler(Client $client, $command);

    /**
     * Client open connection
     * @param Client $client
     * @return mixed
     */
    public function open(Client $client);

    /**
     * Client close connection
     * @param Client $client
     * @return mixed
     */
    public function close(Client $client);
}