<?php

namespace Alexboo\WebSocketHandler;

interface RequestInterface
{
    /**
     * Set data to request
     * @param $data
     */
    public function setData($data);
}