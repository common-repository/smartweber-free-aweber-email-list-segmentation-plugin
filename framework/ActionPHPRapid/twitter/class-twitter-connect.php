<?php

class TwitterConnect
{	
	protected $oauth_token_secret;
	protected $oauth_token;

	public function __construct($consumer_key, $consumer_secret)
	{	
		$this->oauth_consumer_key = $consumer_key;
		$this->oauth_consumer_secret = $consumer_secret;

		$this->oauth_params = array(

			"oauth_consumer_key" => &$this->oauth_consumer_key,
			"oauth_nonce" => time(),
			"oauth_signature_method" => "HMAC-SHA1",
			"oauth_timestamp" => time(),
			"oauth_token" => &$this->oauth_token,
			"oauth_version" => "1.0"

		);
	}

	public function oauth_token($oauth_token)
	{
		$this->oauth_token = $oauth_token;
	}

	public function oauth_token_secret($oauth_token_secret)
	{
		$this->oauth_token_secret = $oauth_token_secret;
	}

	public function get()
	{//@todo get GET requests to work as well
		$base_url = $this->base_url;
		$method = $this->method ="GET";
		$params = $this->params;


	}

	public function post($base_url, $file=false)
	{	
		$this->file = $file;
		$this->base_url = $base_url;
		$method = $this->method ="POST";
		
		$response = $this->connect($file);
		return $response;

	}

	public function connect()
	{
		$url = $this->base_url;

		if($this->file){

			$encoded_params = $this->params;

		} else {

			$encoded_params = $this->stringify($this->params);

		}

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		if(strtoupper($this->method=="POST")){

			curl_setopt($ch,CURLOPT_POSTFIELDS, $encoded_params);
		}

		$authorization_header = $this->authorization_header();

		$headers = array();

		if($this->file){

			$headers[] = "Content-Type:multipart/form-data";
		}

		$headers[] = $authorization_header;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		curl_close($ch);

		return $result;


	}

	public function signature()
	{
		$method = $this->method;
		$params = $this->params;
		$base_url = $this->base_url;
		$oauth_params = $this->oauth_params;

		//OK, so let's build this signature!
		
		//So we add our actual parameters to the signature
		
		if($this->file){

			$request_params = $oauth_params;//When uploading, we only want oauth_*
			// parameters
		} else {
		
			$request_params = array_merge($params, $oauth_params);

		}
		
		ksort($request_params);//Important! We must have our params in alphabetical
		// order!

		$request_string = $this->stringify($request_params);
		

		$signature_base_string = strtoupper($method);

		$signature_base_string .= '&';

		$signature_base_string .= rawurlencode($base_url);

		$signature_base_string .= '&';

		$signature_base_string .= rawurlencode($request_string);

		$consumer_secret = &$this->oauth_consumer_secret;

		$oauth_token_secret = &$this->oauth_token_secret;

		$signature_key = $consumer_secret . '&' .$oauth_token_secret;

		$signature = hash_hmac('SHA1', $signature_base_string, $signature_key, true);
		
		$signature = base64_encode($signature);

		return $signature;

	}

	public function authorization_header()
	{	
		$oauth_params = $this->oauth_params;

		$oauth_params['oauth_signature'] = $this->signature();

		$params = $this->params;

		if($this->file){

			$header_params = $oauth_params;
		
		} else {

			$header_params = array_merge($params, $oauth_params);

		}

		//Let's make sure that the params are arranged in alphabetical order
		ksort($header_params);

		$authorization_header = "OAuth ";

		foreach ($header_params as $key => $value) {
			$authorization_header_params[] = $key . '=' . '"' . rawurlencode($value) . '"';
		}

		$authorization_header .= implode(", ", $authorization_header_params);
		$authorization_header = 'Authorization: ' . $authorization_header;

		return $authorization_header;

	}
	/**
	 * Gets the request token
	 * @return string string containing the ouauth token needed for the
 	 * authorization process
	 */	
	public function request_token($callback_url="")
	{	

		$this->params( array(
			"oauth_callback" => $callback_url
			) 
		);

		return	$this->post('https://api.twitter.com/oauth/request_token');

	}

	public function params($params, $stringify=true)
	{	
		$this->params = $params;

		return $this->params;
	}


	public function stringify($params)
	{		ksort($params); //We are required to have these parameters arranged in
		// alphabetical order by Twitter's OAuth
			$encoded_params = array();
		
			foreach ($params as $key => $value) {

				if(!$this->file){
					$value=rawurlencode($value);
				}

				$encoded_params[] = $key . '=' . $value ;
			}
		
				$request_string = implode('&', $encoded_params);

		$this->encoded_params = $request_string;


		return $this->encoded_params;
	}
}