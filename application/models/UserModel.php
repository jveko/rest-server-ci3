<?php

class UserModel extends CI_Model
{
	public function GetUserWithUsername($username = null)
	{
		$user = $this->db->where('username', $username)->get('users')->result_object();
		if (empty($user)) return null;
		else return $user[0];
	}

	public function Create($username, $email, $password): ?int
	{
		$data = array(
			'username' => $username,
			'email' => $email,
			'password' => $password
		);
		$this->db->set('created_at', 'NOW()', false);
		$this->db->set('updated_at', 'NOW()', false);
		if ($this->db->insert('users', $data)) {
			return $this->db->insert_id();
		};
		return null;
	}

	public function IsEmailExist($email): bool
	{
		$users = $this->db->where('email', $email)->get('users')->result_object();
		if (empty($users)) return false;
		return true;
	}

	public function IsUsernameExist($username): bool
	{
		$users = $this->db->where('username', $username)->get('users')->result_object();
		if (empty($users)) return false;
		return true;
	}
}
