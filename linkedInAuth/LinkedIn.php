<?php

class LinkedIn {

	public $token;
	public $expires_in;
	public $expires_at;

	public function __construct($data) {
		$this->token = $data['token'];
		$this->expires_in = $data['expires_in'];
		$this->expires_at = $data['expires_at'];
	}

	function getName() {
		$method = 'GET';
		$resource = '/v1/people/~:(firstName,lastName)';
		return $this->getRequest($method, $resource);
	}

	function getProfile() {
		$method = 'GET';
		$resource = '/v1/people/~';
		return $this->getRequest($method, $resource);
	}
	
	function getProfileID() {
		$method = 'GET';
		$resource = '/v1/people/~:(id)';
		return $this->getRequest($method, $resource);
	}
	
	function getProfilePretty() {
		$method = 'GET';
		$resource = '/v1/people/~:(id,first-name,last-name,industry,email-address,headline,location,num-connections,picture-url,public-profile-url,site-standard-profile-request,phone-numbers,main-address)';
		return $this->getRequest($method, $resource);
	}
	
	function getProfileLanguage() {
		$method = 'GET';
		$resource = '/v1/people/~:(language)';
		return $this->getRequest($method, $resource);
	}
	

	function getEmail() {
		$method = 'GET';
		$resource = '/v1/people/~/email-address';
		return $this->getRequest($method, $resource);
	}

	function getNetworkUpdate() {
		$method = 'GET';
		$resource = '/v1/people/~/network/updates';
		return $this->getRequest($method, $resource);
	}
	
	function getConnections() {
		$method = 'GET';
		$resource = '/v1/people/~/connections';
		return $this->getRequest($method, $resource);
	}
	
	function getConnectionsBasic() {
		$method = 'GET';
		$resource = '/v1/people/~/connections:(id,first-name,last-name,positions:(title))';
		return $this->getRequest($method, $resource);
	}
	
	function getPeopleSearched() {
		$method = 'GET';
		$resource = '/v1/people/~/people-search';
		return $this->getRequest($method, $resource);
	}
	
	protected function getRequest($method, $resource) {
		$params = array('oauth2_access_token' => $this->token,
			'format' => 'json',
		);

		// Need to use HTTPS
		$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
		// Tell streams to make a (GET, POST, PUT, or DELETE) request
		$context = stream_context_create(
				array('http' =>
					array('method' => $method,
					)
				)
		);

		// Hocus Pocus
		$response = file_get_contents($url, false, $context);

		// Native PHP object, please
		return json_decode($response, true);
	}

}
