<?php

// common
$common = getenv("CommonDir");

define('bEnv', 'dev');
define('bLogLevel', 1);

// bolt
require_once($common."/bolt/src/bolt.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);

// dir
$root = __DIR__;

b::init(array(
    'mode' => 'browser',
    'load' => array(
        $root."/lib/*.php",
        $root.'/controllers/web.php',
        $root.'/controllers/api.php',
        $root.'/view/web.template.php',
    ),
    'config' => array(
        'view' => array(
            'default' => '\metadata\controller\web'
        ),
        'mongo' => array(
            'host' => '127.0.0.1',
            'port' => '27017',
            'db' => "metadata"
        )
    )
));

// run
b::run();