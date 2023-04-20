<?php

use chriskacerguis\RestServer\RestController;

class User extends RestController
{
	public UserModel $user;

	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('UserModel', 'user');
	}

	public function Index_POST()
	{
		$this->user->Create('Test', 'test@gmail.com', password_hash('Testing', PASSWORD_BCRYPT));
		$this->response(['message' => "Success"], RestController::HTTP_OK);
	}
}
