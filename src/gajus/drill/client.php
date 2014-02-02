<?php
namespace gajus\drill;

/**
 * @link https://github.com/gajus/drill for the canonical source repository
 * @copyright Copyright (c) 2013-2014, Anuary (http://anuary.com/)
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
			throw new \InvalidArgumentException('Endpoint path must not start with /.');
		}

		if (strpos($path, '.') !== false) {
			throw new \InvalidArgumentException('Endpoint must not include output format.');
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

		// @todo check for non 200 response, https://mandrillapp.com/api/docs/
		
		if (curl_errno($ch)) {			
			throw new \Exception(error_get_last()['message']);
		}
		
		curl_close($ch);

		$response = json_decode($response, true);

		return $response;
	}
}