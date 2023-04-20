<?php

require_once(APPPATH . 'controllers/BaseController.php');

class  Auth extends BaseController
{
	public UserModel $user;
	public TokenModel $token;

	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
		$this->load->model('TokenModel', 'token');
	}

	public function InvalidCredentialsResponse()
	{
		$this->response(
			[
				'message' => 'Invalid username or password'
			], self::HTTP_UNAUTHORIZED
		);
	}


	public function login_POST()
	{
		$user = $this->user->GetUserWithUsername($this->post('username'));
		if ($user == null) $this->InvalidCredentialsResponse();
		if (!password_verify($this->post('password'), $user->password)) $this->InvalidCredentialsResponse();
		$accessToken = $this->GenerateAccessToken($user->id);
		$refreshToken = $this->GenerateRefreshToken($user->id);
		$this->token->Create($user->id, $accessToken, $refreshToken);
		$this->response(
			[
				'status' => 'success',
				'data' => [
					'refreshToken' => $refreshToken,
					'accessToken' => $accessToken,
				]
			], self::HTTP_OK
		);
	}

	public function session_PUT()
	{
		$refreshToken = $this->put('refreshToken');
		if (!$refreshToken)
			$this->response(
				[
					'message' => "Refresh Token should be exist"
				], self::HTTP_UNAUTHORIZED
			);
		$decodeRefreshToken = $this->ValidateRefreshToken($refreshToken);
		if ($decodeRefreshToken == null)
			$this->response(
				[
					'message' => "Refresh Token not Valid, Please Re-Login"
				], self::HTTP_UNAUTHORIZED
			);
		$accessToken = $this->token->GetAccessToken($refreshToken, $decodeRefreshToken->user_id);
		if ($accessToken == null)
			$this->response(
				[
					'message' => "Access Token not exist, Please Re-Login"
				], self::HTTP_UNAUTHORIZED
			);
		$accessToken = $this->GenerateAccessToken($decodeRefreshToken->user_id);
		$this->token->UpdateAccessToken($refreshToken, $decodeRefreshToken->user_id, $accessToken);
		$this->response(
			[
				'status' => 'success',
				'data' => [
					'accessToken' => $accessToken,
				]
			], self::HTTP_OK
		);
	}
}
