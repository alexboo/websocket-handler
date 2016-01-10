<?php

namespace Alexboo\WebSocketHandler;

use Alexboo\WebSocketHandler\Exceptions\WebSocketHandlerException;

class Request
{
    public $command;
    public $data;

    public function __construct($data)
    {
        try {
            $data = json_decode($data);

            if (isset($data->command) && isset($data->data)) {
                $this->command = $data->command;
                $this->data = $data->data;
            } else {
                throw new WebSocketHandlerException("Wrong request");
            }
        } catch (\Exception $e) {
            throw new WebSocketHandlerException($e->getMessage());
        }

    }
}