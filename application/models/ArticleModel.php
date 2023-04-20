<?php

class ArticleModel extends CI_Model
{
	public function GetArticle($id)
	{
		$article = $this
			->db
			->where('id', $id)
			->get('articles')
			->result_object();
		if (!empty($article)) return $article[0];
		else return null;
	}

	public function GetArticles(): array
	{
		return $this
			->db
			->get('articles')
			->result_array();
	}
	public function GetArticlesWithUserId($user_id): array
	{
		return $this
			->db
			->where('created_by_id', $user_id)
			->get('articles')
			->result_array();
	}

	public function Create($title, $content, $user_id): ?int
	{
		$data = array(
			'title' => $title,
			'content' => $content,
			'created_by_id' => $user_id,
			'updated_by_id' => $user_id
		);
		$this->db->set('created_at', 'NOW()', false);
		$this->db->set('updated_at', 'NOW()', false);
		if ($this->db->insert('articles', $data))
			return $this->db->insert_id();
		return null;
	}

	public function UpdateTitle($id, $title, $user_id): bool
	{
		$data = array(
			'title' => $title,
			'updated_by_id' => $user_id
		);
		$this->db->set('updated_at', 'NOW()', false);
		return $this->db->where('id', $id)->update('articles', $data);
	}

	public function UpdateContent($id, $content, $user_id): bool
	{
		$data = array(
			'content' => $content,
			'updated_by_id' => $user_id
		);
		$this->db->set('updated_at', 'NOW()', false);
		return $this->db->where('id', $id)->update('articles', $data);
	}

	public function Update($id, $title, $content, $user_id): bool
	{
		$data = array(
			'title' => $title,
			'content' => $content,
			'updated_by_id' => $user_id
		);
		$this->db->set('updated_at', 'NOW()', false);
		return $this->db->where('id', $id)->update('articles', $data);
	}

	public function Delete($id)
	{
		return $this->db->where('id', $id)->delete('articles');
	}
}
