<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Redis;

class GetPhoneInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phone:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get phone status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        Redis::select(1);
        $queue = 'appStatus';
        $connection = AMQPStreamConnection::create_connection([
            ['host' => env('MQ_Local_HOST'), 'port' => env('MQ_PORT'), 'user' => env('MQ_USER'), 'password' => env('MQ_PWD'), 'vhost' => env('MQ_VHOST')],
        ],
            ['read_write_timeout'=>60,'heartbeat'=>30]);
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, false, false, false);
        $callback = function ($msg) {
            $this->phoneStatusCheck($msg->body);
        };
        $channel->basic_consume($queue, '', false, true, false, false, $callback);
        while ($channel->callbacks) {
            $channel->wait();
        }
    }

    protected function phoneStatusCheck($json_str)
    {
        $data = json_decode($json_str, true);
        $key  = $data['phoneid'].$data['type'];
        if(Redis::exists($key))
        {
            $params = Redis::hGetAll($key);

            if( date('Y-m-d', strtotime($params['update'])) != date('Y-m-d', time()) )
            {
                Redis::hset($key, 'amount', 0);
                Redis::hset($key, 'bankamount', 0);
            }

            Redis::hset($key, 'status', $data['status']);
            Redis::hset($key, 'update', date('Y-m-d H:i:s', time()));
            if($data['account']){
                Redis::hset($key, 'account',$data['account']);
            }
            Redis::hset($key, 'userid',$data['alipayuserid']);
            Redis::hset($key, 'comment',$data['comment']);
        }else{
            $params = array(
                'phoneid' => $data['phoneid'],
                'amount'  => 0,   // 交易额
                'bankamount' => 0,// 银行卡交易额
                'weight'  => 1,
                'update'  => date('Y-m-d H:i:s', time()),
                'comment' => $data['comment'],
                'type'    => $data['type'],
                'account' => $data['account'],
                'userid'  => $data['alipayuserid'],
                'status'  => $data['status'],
            );
            Redis::hmset($key,$params);
        }
    }
}
