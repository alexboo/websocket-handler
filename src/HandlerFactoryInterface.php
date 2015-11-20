<?php

namespace Alexboo\WebSocketHandler;

interface HandlerFactoryInterface
{
    /**
     * Get handler for command
     * @param Client $client
     * @param $command
     * @return Handler
     */
    public function getHandler(Client $client, $command);
}