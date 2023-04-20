<?php

require_once(APPPATH . 'controllers/BaseController.php');

class User extends BaseController
{
	public UserModel $user;

	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('UserModel', 'user');
	}

	public function Index_POST()
	{
		$username = $this->post('username');
		$email = $this->post('email');
		$password = $this->post('password');
		if (!$username && !$email && $password)
			$this->response(
				[
					'message' => "Username, Email, Password is Required"
				], self::HTTP_BAD_REQUEST
			);

		if ($this->user->IsUsernameExist($username))
			$this->response(
				[
					'message' => "Username Already Exist"
				], self::HTTP_BAD_REQUEST
			);

		if ($this->user->IsEmailExist($email))
			$this->response(
				[
					'message' => "Email Already Exist"
				], self::HTTP_BAD_REQUEST
			);

		$returnedId = $this->user->Create($username, $email, password_hash($password, PASSWORD_BCRYPT));
		if ($returnedId != null) {
			$this->response(
				[
					'status' => "success",
					'message' => "User Created",
					'data' => [
						'id' => $returnedId
					]
				], self::HTTP_CREATED);
		}
		$this->response(
			[
				'message' => "Internal Server Error",
			], self::HTTP_INTERNAL_ERROR);
	}
}
