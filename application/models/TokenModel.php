<?php

class TokenModel extends CI_Model
{
	public function GetAccessToken($refresh_token, $user_id)
	{
		$token = $this->db
			->where('refresh_token', $refresh_token)
			->where('user_id', $user_id)
			->get('tokens')->result_object();
		if (!empty($token)) return $token[0]->access_token;
		return null;
	}

	public function UpdateAccessToken($refresh_token, $user_id, $access_token): bool
	{
		return $this->db
			->where('refresh_token', $refresh_token)
			->where('user_id', $user_id)
			->update('tokens', ['access_token' => $access_token]);
	}

	public function Create($user_id, $access_token, $refresh_token)
	{
		$data = array(
			'user_id' => $user_id,
			'access_token' => $access_token,
			'refresh_token' => $refresh_token
		);
		$this->db->insert('tokens', $data);
	}

	public function Delete($refresh_token)
	{
		return $this->db->where('refresh_token', $refresh_token)->delete('tokens', $data);
	}


}
