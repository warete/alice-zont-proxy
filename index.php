<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv,
	AliceZontProxy\ZontClient;

try
{
	if (file_exists(__DIR__.'/.env'))
	{
		$dotenv = Dotenv::createImmutable(__DIR__);
    	$dotenv->load();
	}    
}
catch(Throwble $e)
{

}

if (!isset($_REQUEST['secret']) && $_REQUEST['secret'] != getenv('SECRET'))
{
	header('HTTP/1.0 403 Forbidden');
	die('Forbidden');
}

$zontClient = new ZontClient(
	getenv('ZONT_API_URL'),
	getenv('ZONT_LOGIN'),
	getenv('ZONT_PASSWORD'),
	getenv('ZONT_EMAIL'),
	getenv('DEVICE_ID'),
	getenv('DEBUG') == "true" ? true : false
);

$payload = json_decode(file_get_contents("php://input"), true);

if (count($payload) && isset($payload['value']))
{
	switch (intval($payload['value']))
    {
    	//Открыть машину
		case 1:
			$zontClient->setGuardState(false);
			break;
		//Закрыть машину
		case 2:
			$zontClient->setGuardState(true);
			break;
		//Завести машину
		case 3:
			$zontClient->setEngineState(true);
			break;
		//Заглушить машину
		case 4:
			$zontClient->setEngineState(false);
			break;
		//Логируем на всякий случай неизвестные значения
		default:
			$tempFile = fopen(__DIR__ . '/log/alice_zont_unknown_values_log.log', 'a');
			fwrite(
			    $tempFile,
			    __FILE__ . ':' . __LINE__ . PHP_EOL . '(' . date('Y-m-d H:i:s').')' . PHP_EOL
			    . print_r($payload, TRUE)
			    . PHP_EOL . PHP_EOL
			);
			fclose($tempFile);
    }
}
else
{
	echo json_encode(['healthcheck' => 'ok']);
}
