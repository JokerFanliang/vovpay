<?php

namespace App\Services;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqService{
    /**
     * @var object $connection 队列连接对象
     */
    private $connection = null;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(env('MQ_Local_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PWD'),env('MQ_VHOST'));
    }

    /**
     * 发送消息
     * @param $queue_name
     * @param $messAge
     */
    public function send($queue_name,$messAge)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue_name, false, false, false, false);

        $msg = new AMQPMessage($messAge);
        $channel->basic_publish($msg, '', $queue_name);

        $channel->close();
        $this->connection->close();
    }
}