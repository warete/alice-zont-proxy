<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv,
	AliceZontProxy\ZontClient;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_REQUEST['secret']) && $_REQUEST['secret'] != getenv('SECRET'))
{
    die();
}

$zontClient = new ZontClient(
	getenv('ZONT_API_URL'),
	getenv('ZONT_LOGIN'),
	getenv('ZONT_PASSWORD'),
	getenv('ZONT_EMAIL'),
	getenv('DEVICE_ID'),
	getenv('DEBUG') == "true" ? true : false
);
