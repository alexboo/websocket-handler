<?php

namespace Alexboo\WebSocketHandler;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketHandler extends Output implements MessageComponentInterface
{
    /**
     * @var Storage
     */
    protected $_clients;

    /**
     * @var HandlerFactoryInterface
     */
    protected $_handlerFactory;

    private static $_instance;

    public static function getInstance(HandlerFactoryInterface $factoryInterface = null)
    {
        if (empty(self::$_instance)) {
            self::$_instance = new WebSocketHandler($factoryInterface);
        }
        return self::$_instance;
    }

    private function __construct(HandlerFactoryInterface $factoryInterface = null)
    {
        $this->_clients = new Storage();
        if ($factoryInterface != null) {
            $this->_handlerFactory = $factoryInterface;
        } else {
            $this->error("Not found HandlerFactory");
            throw new WebSocketHandlerException("Not found HandlerFactory");
        }
    }

    /**
     * Get full list of clients
     * @return Storage
     */
    public function getClients()
    {
        return $this->_clients;
    }

    /**
     * Set handler factory
     * @return HandlerFactoryInterface
     */
    public function getHandlerFactory()
    {
        return $this->_handlerFactory;
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $client = new Client($conn);
            $this->_clients->set($conn->resourceId, $client);
        $this->_handlerFactory->open($client);
        $this->out($this->getColoredText(self::FG_COLOR_GREEN, "Client " . $conn->resourceId . " connected to server"));
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $client = $this->_clients->get($conn->resourceId);
        $this->_handlerFactory->close($client);
        $this->_clients->remove($client->getResourceId());
        unset($client);
        $this->out($this->getColoredText(self::FG_COLOR_RED, "Client " . $conn->resourceId . " close connect to server"));
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        /**
         * @var Client $client
         */
        $client = $this->_clients->get($conn->resourceId);
        $response = new Response();
        $response->error = $e->getMessage();
        $client->send($response);
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        /**
         * @var Client $client
         */
        $client = $this->_clients->get($from->resourceId);
        $request = new Request($msg);
        if (!empty($request->command)) {
            $response = new Response();
            $response->command = $request->command;
            try {
                /**
                 * @var Handler $handler
                 */
                $handler = $this->_handlerFactory->getHandler($client, $request->command);
                if (method_exists($handler, 'execute')) {
                    $response->data = $handler->execute($client, $request->data);
                    $client->send($response);
                } else {
                    $this->error("Not found handler for command");
                    throw new WebSocketHandlerException("Not found handler for command");
                }
            } catch (\Exception $e) {
                $response->error = $e->getMessage();
                $client->send($response);
                $this->error($this->getColoredText(self::FG_COLOR_RED, $e->getMessage()));
                $this->error($e->getTraceAsString());
            }
        }
    }
}