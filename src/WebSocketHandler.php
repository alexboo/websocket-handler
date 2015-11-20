<?php

namespace Alexboo\WebSocketHandler;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketHandler implements MessageComponentInterface
{
    /**
     * @var Storage
     */
    protected $_clients;

    /**
     * @var HandlerFactoryInterface
     */
    protected $_handlerFactory;

    public function __construct(HandlerFactoryInterface $factoryInterface = null)
    {
        $this->_clients = new Storage();
        if ($factoryInterface != null) {
            $this->_handlerFactory = $factoryInterface;
        } else {
            throw new WebSocketHandlerException("Not found HandlerFactory");
        }
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
        $this->_clients->set($conn->resourceId, new Client($conn));
        echo "connect to server\n";
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->_clients->remove($conn->resourceId);
        echo "close connect to server\n";
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
            try {
                /**
                 * @var Handler $handler
                 */
                $handler = $this->_handlerFactory->getHandler($client, $request->command);
                if (method_exists($handler, 'execute')) {
                    $response->command = $request->command;
                    $response->data = $handler->execute($client, $request->data);
                    $client->send($response);
                } else {
                    throw new WebSocketHandlerException("Not found handler for command");
                }
            } catch (\Exception $e) {
                $response->error = $e->getTraceAsString();
                $client->send($response);
            }
        }
    }
}