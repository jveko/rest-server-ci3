<?php

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseController extends RestController
{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
	}

	private function accessTokenEncode($payload): string
	{
		return JWT::encode($payload, JWT_SECRET_ACCESS, JWT_ALGORITHM);
	}


	private function refreshTokenEncode($payload): string
	{
		return JWT::encode($payload, JWT_SECRET_REFRESH, JWT_ALGORITHM);
	}

	private function accessTokenDecode($token): stdClass
	{
		return JWT::decode($token, new Key(JWT_SECRET_ACCESS, JWT_ALGORITHM));
	}


	private function refreshTokenDecode($token): stdClass
	{
		return JWT::decode($token, new Key(JWT_SECRET_REFRESH, JWT_ALGORITHM));
	}


	protected function GenerateAccessToken($user_id): string
	{
		// Set the token payload
		$token_payload = array(
			'user_id' => $user_id,
			'exp' => time() + 3600, // Expiration time in seconds
		);

		// Generate the token using the Firebase JWT library
		return $this->accessTokenEncode($token_payload);
	}

	protected function GenerateRefreshToken($user_id): string
	{
		// Set the token payload
		$token_payload = array(
			'user_id' => $user_id,
			'exp' => time() + (3600 * 24), // Expiration time in seconds
		);

		// Generate the token using the Firebase JWT library
		return $this->refreshTokenEncode($token_payload);
	}

	protected function VerifyToken()
	{
		// Get the authorization header from the request
		$authorization_header = $this->head('Authorization');
		if ($authorization_header == null) $this->response(array('error' => 'Invalid token'), 401);
		// Extract the JWT token from the authorization header
		$token = str_replace('Bearer ', '', $authorization_header);
		// Verify the JWT token
		try {
			return $this->accessTokenDecode($token);
		} catch (Exception $e) {
			// Invalid token
			$this->response(array('error' => 'Invalid token'), 401);
		}

		// The token is valid, proceed with the API request
	}

	protected function ValidateRefreshToken($refreshToken): ?stdClass
	{
		try {
			return $this->refreshTokenDecode($refreshToken);
		} catch (Exception $e) {
			return null;
		}
	}

	protected function ValidateAccessToken($accessToken): ?stdClass
	{
		try {
			return $this->accessTokenDecode($accessToken);
		} catch (Exception $e) {
			return null;
		}
	}
}
