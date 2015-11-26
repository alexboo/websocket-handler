<?php

namespace Alexboo\WebSocketHandler;

use ReflectionClass;
use ReflectionParameter;

class Handler
{
    protected $_object;
    protected $_method;
    protected $_closure;

    protected $_client;
    protected $_data;

    /**
     * Set object like handler
     * @param $object
     * @param $method
     * @throws WebSocketHandlerException
     */
    public function setObject($object, $method)
    {
        if (empty($object) || empty($method)) {
            throw new WebSocketHandlerException("You must specify a processing entity");
        }

        $this->_object = $object;
        $this->_method = $method;
    }

    /**
     * Set processing closure
     * @param \Closure $closure
     * @throws WebSocketHandlerException
     */
    public function setClosure(\Closure $closure)
    {
        if (empty($closure)) {
            throw new WebSocketHandlerException("You must specify a processing entity");
        }

        $this->_closure = $closure;
    }

    /**
     * Execute handler
     * @param Client $client
     * @param $data
     * @return mixed|null
     */
    public function execute(Client $client, $data)
    {
        $this->_client = $client;
        $this->_data = $data;

        // Execute handler object
        if (!empty($this->_object)) {
            $class = new ReflectionClass(get_class($this->_object));
            $method = $class->getMethod($this->_method);
            $parameters = $this->prepareParameters($method->getParameters());
            if (empty($parameters)) {
                $parameters[] = $data;
            }
            return call_user_func_array([$this->_object, $this->_method], $parameters);
        }

        // Execute closure handler
        if (!empty($this->_closure)) {
            $function = new \ReflectionFunction($this->_closure);
            $parameters = $this->prepareParameters($function->getParameters());
            if (empty($parameters)) {
                $parameters[] = $data;
            }
            return call_user_func_array($this->_closure, $parameters);
        }

        return null;
    }

    /**
     * Prepare parameters for handler
     * @param $parameters
     * @return array
     */
    protected function prepareParameters($parameters)
    {
        $result = [];

        foreach ($parameters as $parameter) {
            /**
             * @var ReflectionParameter $parameter
             */
            $paramClass = $parameter->getClass();
            if ($paramClass != null) {
                if ($paramClass->getName() == 'Alexboo\WebSocketHandler\Client') {
                    $result[] = $this->_client;
                } elseif ($paramClass->implementsInterface('Alexboo\WebSocketHandler\RequestInterface')) {
                    $result[] = new $paramClass->name($this->_data);
                } else {
                    $result[] = null;
                }
            }
        }

        return $result;
    }
}