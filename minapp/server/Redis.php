<?php

include './predis-1.1/autoload.php';

class Redis {
    public static $client;
    static $single_server = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
    );

    static $multiple_servers = array(
        array(
           'host' => '127.0.0.1',
           'port' => 6379,
           'database' => 15,
           'alias' => 'first',
        ),
        array(
           'host' => '127.0.0.1',
           'port' => 6380,
           'database' => 15,
           'alias' => 'second',
        ),
    );
    private function __construct() {
        
    }
    public static function getInstance() {
        if (!self::$client) {
            self::$client = new Predis\Client(self::$single_server);
        }
        return self::$client;
    }

}
