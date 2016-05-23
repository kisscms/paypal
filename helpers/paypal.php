<?php
/* Paypal for KISSCMS */
class Paypal {

	public $name = "paypal";
	private $api;
	private $oauth;
	private $config;
	private $creds;
	private $cache;

	function  __construct() {
		// main URL
		$this->api = "https://api.paypal.com/";

		// load all the necessery subclasses
		$this->oauth = new Paypal_OAuth();

		$this->config = $GLOBALS['config']['paypal'];
		// get/update the creds
		$this->creds = $this->oauth->creds();
		// when no user token use an application token
		//if( !$this->creds ) $this->creds = $this->token();

	}

	function request($method='GET', $service="", $params=array() ){

		$url = $this->api . $service;
		// add access token
		if( empty($params["oauth_token"]) ) {
			if( !empty($this->creds["access_token"]) ){
				$params["oauth_token"] = $this->creds["access_token"];
			} else {
				// use application creentials
				$params["client_id"] = $this->config['key'];
				$params["client_secret"] = $this->config['secret'];
			}
		}

		$http = new Http();
		$http->setMethod($method);
		$http->setParams( $params );
		$http->execute( $url );

		if($http->error) die($http->error);

		// decode json string as a php object
		$results = json_decode($http->result);
		// check if the response if valid
		// paypal does't return an error code ($results->meta->code), rather just points to the documentation :P
		$valid = empty($results->documentation_url);

		// log errors...

		// just return a repsonse  when valid (or the whole response to display error messages)
		return ($valid) ? $results : json_decode("{}");

	}

	// REST methods
	function  get( $service="", $params=array() ){

		// check cache before....
		//...
		$results = $this->request('GET', $service, $params );

		return $results;

	}


	function  post( $service="", $params=array() ){

		$results = $this->request('POST', $service, $params );

		return $results;

	}

	function  put( $service="", $params=array() ){

		$results = $this->request('PUT', $service, $params );

		return $results;

	}

	function  delete( $service="", $params=array() ){

		$results = $this->request('DELETE', $service, $params );

		return $results;

	}

	// Helpers
	// - registers application token
/*
	function token(){
		$params = array(
			"scope" => "", // add custom scope here: http://developer.github.com/v3/oauth/#scopes
			"note" => "application token",
			"client_id" => $this->config['key'],
			"client_secret" => $this->config['secret']
		);
		$results = $this->request('POST', "authorizations", $params );
		// convert object to array
		$results = (array) $results;
		// save for future requests
		$this->oauth->creds( $results );
		return $results;
	}
*/
}

?>
