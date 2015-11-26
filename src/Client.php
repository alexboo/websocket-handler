<?php

namespace Alexboo\WebSocketHandler;

class Client
{
    /**
     * @var \Ratchet\ConnectionInterface
     */
    protected $connect;

    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(\Ratchet\ConnectionInterface $connect, StorageInterface $storage = null)
    {

        if (!empty($connect)) {
            $this->connect = $connect;
        } else {
            throw new WebSocketHandlerException("Not found resourceId");
        }

        $this->setStorage($storage);
    }

    /**
     * Set storag
     * @param StorageInterface|null $storage
     */
    public function setStorage(StorageInterface $storage = null)
    {
        if ($storage != null) {
            $this->storage = $storage;
        } else {
            $this->storage = new Storage();
        }
    }

    /**
     * Get storage
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get client resource id
     * @return mixed
     */
    public function getResourceId()
    {
        return $this->connect->resourceId;
    }

    /**
     * Get connect
     * @return \Ratchet\ConnectionInterface
     */
    public function getConnect()
    {
        return $this->connect;
    }

    /**
     * Set connect
     * @param \Ratchet\ConnectionInterface $connect
     */
    public function setConnect(\Ratchet\ConnectionInterface $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Send response
     * @param Response $response
     */
    public function send(Response $response)
    {
        $this->connect->send((string)$response);
    }

    /**
     * Create response for send
     * @param $command
     * @param null $data
     * @param null $error
     * @return Response
     */
    public function createResponse($command, $data = null, $error = null)
    {
        $response = new Response();
        $response->command = $command;
        if (!empty($data)) {
            $response->data = $data;
        }
        if (!empty($error)) {
            $response->error = $error;
        }
        return $response;
    }

    public function __set($name, $value)
    {
        $this->storage->set($name, $value);
    }

    public function __get($name)
    {
        return $this->storage->get($name);
    }

    public function __unset($name)
    {
        $this->storage->remove($name);
    }
}