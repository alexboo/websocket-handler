<?php

namespace Alexboo\WebSocketHandler;

use Iterator;

class Storage implements StorageInterface, Iterator
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

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_storage);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_storage);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_storage);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->_storage[$this->key()]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_storage);
    }
}