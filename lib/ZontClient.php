<?php
namespace AliceZontProxy;

use GuzzleHttp\Client,
	GuzzleHttp\Exception\ClientException;

class ZontClient
{
	/** @var Client|null GuzzleHttp client instance */
	protected $client = null;
	/** @var null Zont device id */
	protected $deviceId = null;

	/**
	 * ZontClient constructor.
	 * @param $apiUrl
	 * @param $login
	 * @param $password
	 * @param $email
	 * @param $deviceId
	 * @param $debug
	 */
	public function __construct($apiUrl, $login, $password, $email, $deviceId, $debug = false)
	{
		$this->deviceId = $deviceId;
		$this->client = new Client([
			'base_uri' => $apiUrl,
			'auth' => [$login, $password],
			'headers' => [
				'X-ZONT-Client' => $email,
			],
			'debug' => $debug,
		]);
	}

	/**
	 * @param $method
	 * @param $endpoint
	 * @param $params
	 * @return mixed|null
	 */
	public function request($method, $endpoint, $params)
	{
		$result = null;
		try
		{
			$response = $this->client->request($method, $endpoint, $params);
			$result = json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			$tempFile = fopen(__DIR__ . '/log/alice_zont_log.log', 'a');
			fwrite(
				$tempFile,
				__FILE__ . ':' . __LINE__ . PHP_EOL . '(' . date('Y-m-d H:i:s').')' . PHP_EOL
				. print_r($e->getTraceAsString(), TRUE)
				. PHP_EOL . PHP_EOL
			);
			fclose($tempFile);
		}

		return $result;
	}
}