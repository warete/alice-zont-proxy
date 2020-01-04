<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv,
	GuzzleHttp\Client;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Client([
	'base_uri' => getenv('ZONT_API_URL'),
	'auth' => [getenv('ZONT_LOGIN'), getenv('ZONT_PASSWORD')],
	'headers' => [
		'X-ZONT-Client' => getenv('ZONT_EMAIL'),
	],
	'debug' => getenv('DEBUG') == "true" ? true : false,
]);
