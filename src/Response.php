<?php

namespace Alexboo\WebSocketHandler;


class Response
{
    public $command;
    public $data;
    public $error;

    public function __toString()
    {
        $result = [];

        if (!empty($this->command)) {
            $result['command'] = $this->command;
        }

        if (!empty($this->data)) {
            $result['data'] = $this->data;
        }

        if (!empty($this->error)) {
            $result['error'] = $this->error;
        }

        return json_encode($result);
    }
}