<?php

namespace Alexboo\WebSocketHandler;

class Storage implements StorageInterface
{
    protected $_storage = [];

    /**
     * Set data to storage
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->_storage[$name] = $value;
    }

    /**
     * Get data from storage
     * @param $name
     * @return null
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->_storage[$name];
        }

        return null;
    }

    /**
     * Check that has data in storage
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_storage[$name]);
    }

    /**
     * Remove data from storage
     * @param $name
     */
    public function remove($name)
    {
        unset($this->_storage[$name]);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __unset($name)
    {
        $this->remove($name);
    }
}