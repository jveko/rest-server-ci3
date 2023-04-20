<?php

class UserModel extends CI_Model
{
	public function GetUserWithUsername($username = null)
	{
		$user = $this->db->where('username', $username)->get('users')->result_object();
		if (empty($user)) return null;
		else return $user[0];
	}

	public function Create($username, $email, $password)
	{
		$data = array(
			'username' => $username,
			'email' => $email,
			'password' => $password
		);
		$this->db->set('created_at', 'NOW()', false);
		$this->db->set('updated_at', 'NOW()', false);
		$this->db->insert('users', $data);
	}
}
