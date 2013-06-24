<?php
namespace ay\mindrill;

class Mindrill {
	private
		$api_key;

	public function __construct ($key) {
		$this->api_key = $key;
	}
	
	public function api ($path, array $parameters = []) {
		$parameters['key'] = $this->api_key;
		
		// @see https://mandrillapp.com/api/docs/messages.JSON.html
		
		$endpoint = 'https://mandrillapp.com/api/1.0/' . $path . '.json';
		
		$ch = curl_init();
		
		curl_setopt_array($ch, [
			CURLOPT_URL => $endpoint,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
			CURLOPT_POSTFIELDS => json_encode($parameters)
		]);
		
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {			
			throw new Error_Exception(error_get_last()['message']);
		}
		
		curl_close($ch);
		
		return json_decode($response, true);
	}
}