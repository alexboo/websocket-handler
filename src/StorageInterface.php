<?php

namespace Alexboo\WebSocketHandler;

interface StorageInterface
{
    /**
     * Set data to storage
     * @param $name
     * @param $value
     */
    public function set($name, $value);

    /**
     * Get data from storage
     * @param $name
     * @return null
     */
    public function get($name);

    /**
     * Remove data from storage
     * @param $name
     */
    public function remove($name);

    /**
     * Check that has data in storage
     * @param $name
     * @return bool
     */
    public function has($name);
}