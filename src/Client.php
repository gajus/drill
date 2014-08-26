<?php
namespace Gajus\Drill;

/**
 * @link https://github.com/gajus/drill for the canonical source repository
 * @license https://github.com/gajus/drill/blob/master/LICENSE BSD 3-Clause
 */
class Client {
	private
		$api_key;

	/**
	 * @param string $key Mandrill API key.
	 */
	public function __construct ($api_key) {
		$this->api_key = $api_key;
	}
	
	/**
	 * @see https://mandrillapp.com/api/docs/messages.JSON.html
	 * @param string $path
	 * @param array $parameters
	 */
	public function api ($path, array $parameters = []) {
		$parameters['key'] = $this->api_key;

		if (strpos($path, '/') === 0) {
			throw new Exception\InvalidArgumentException('Endpoint path must not start with /.');
		}

		if (strpos($path, '.') !== false) {
			throw new Exception\InvalidArgumentException('Endpoint must not include output format.');
		}
		
		$endpoint = 'https://mandrillapp.com/api/1.0/' . $path . '.json';
		
		$ch = curl_init();
		
		curl_setopt_array($ch, [
			CURLOPT_URL => $endpoint,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_POST => true,
			CURLOPT_USERAGENT => 'Drill-PHP/0.0.1',
			CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
			CURLOPT_POSTFIELDS => json_encode($parameters)
		]);
		
		$response = curl_exec($ch);

		$response = json_decode($response, true);

		if (curl_getinfo($ch, \CURLINFO_HTTP_CODE) !== 200) {
			$error_name = 'Gajus\Drill\Exception\\RuntimeException\\' . $this->toCamelCase($response['name']) . 'Exception';

			if (class_exists($error_name)) {
				throw new $error_name ($response['message']);
			} else {
				throw new \Gajus\Drill\Exception\RuntimeException($response['message']);
			}
		}

		/**
		 * @see ClientTest::testRequestWithoutRequiredParameters
		 */
		if ($response === []) {
			throw new \Gajus\Drill\Exception\RuntimeException('Missing required parameters.');
		}

		curl_close($ch);

		return $response;
	}

	static private function toCamelCase ($input) {
		return str_replace('_', '', $input);
	}
}