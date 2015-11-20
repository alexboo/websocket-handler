<?php

namespace Alexboo\WebSocketHandler;

use ReflectionClass;

class Handler
{
    protected $class;
    protected $methodName;

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param mixed $methodName
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    public function execute(Client $client, $data)
    {
        if (is_object($this->class)) {
            $class = new ReflectionClass(get_class($this->class));

            $method = $class->getMethod($this->methodName);

            $parameters = [];

            foreach ($method->getParameters() as $parameter) {
                $paramClass = $parameter->getClass();
                if ($paramClass != null) {
                    if ($paramClass->getName() == 'Alexboo\WebSocketHandler\Client') {
                        $parameters[] = $client;
                    } elseif ($paramClass->implementsInterface('Alexboo\WebSocketHandler\RequestInterface')) {
                        $parameters[] = new $paramClass->name($data);;
                    } else {
                        $parameters[] = null;
                    }
                }
            }

            if (empty($parameters)) {
                $parameters[] = $data;
            }

            return call_user_func_array([$this->class, $this->methodName], $parameters);
        }

        return null;
    }
}