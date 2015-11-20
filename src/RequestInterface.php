<?php

namespace Alexboo\WebSocketHandler;

interface RequestInterface
{
    /**
     * Set data to request
     * @param $data
     * @return mixed
     */
    public function setData($data);
}