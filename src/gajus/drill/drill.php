<?php
namespace gajus\drill;

class Drill {
	
	private
		$api_key;

	/**
	 * @param string $key Mandrill API key.
	 */
	public function __construct ($key) {
		$this->api_key = $key;
	}
	
	/**
	 * @see https://mandrillapp.com/api/docs/messages.JSON.html
	 */
	public function api ($path, array $parameters = []) {
		$parameters['key'] = $this->api_key;
		
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