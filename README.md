# WebSocketHandler

A small library to define a handler for web socket command

## Install 

You can install it library with composer 

```
composer require alexboo/websocket-handler
```
## Using

Yoy need create Handler factory for create handler for the processing of each command. It must implements HandlerFactoryInterface. In Handler factory you can add ACL before create handler.
 
### Example handler factory
```

class HandlerFactory implements HandlerFactoryInterface
{
    /**
     * Get handler for command
     * @param Client $client
     * @param $command
     * @return Handler
     */
    public function getHandler(Client $client, $command)
    {    
        $handler = new Handler();
        switch ($command) {
            case "auth":
                $handler->setObject(new AuthService(), "auth");
                break;
            case "message":
                $handler->setClosure(function(){
                    // do something
                });
                break;
        }

        return $handler;
    }

    /**
     * Client open connection
     * @param Client $client
     * @return mixed
     */
    public function open(Client $client){

    }

    /**
     * Client close connection
     * @param Client $client
     * @return mixed
     */
    public function close(Client $client)
    {
    }
}

```

Then you can start web socket server

```
$wsHandler =  WebSocketHandler::getInstance(new HandlerFactory());

$ioServer = IoServer::factory(
    new HttpServer(new \Ratchet\WebSocket\WsServer($wsHandler)),
    8001
);

$ioServer->run();

```