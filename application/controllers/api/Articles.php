<?php

require_once(APPPATH . 'controllers/BaseController.php');

class Articles extends BaseController
{
	public ArticleModel $article;

	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('ArticleModel', 'article');
	}

	public function NotFoundResponse()
	{
		$this->response(
			[
				'message' => 'Article Not Found'
			], self::HTTP_NOT_FOUND
		);
	}

	public function Index_GET()
	{
		$this->VerifyToken();
		$articles = $this->article->GetArticles();
		$this->response(
			[
				'status' => 'success',
				'data' => $articles
			], self::HTTP_OK
		);
	}

	public function Me_GET()
	{
		$decodedToken = $this->VerifyToken();
		$articles = $this->article->GetArticlesWithUserId($decodedToken->user_id);
		$this->response(
			[
				'status' => 'success',
				'data' => $articles
			], self::HTTP_OK
		);
	}

	public function Index_POST()
	{
		$decodedToken = $this->VerifyToken();
		$returnId = $this
			->article
			->Create(
				$this->post('title'),
				$this->post('content'),
				$decodedToken->user_id
			);
		if ($returnId != null)
			$this->response(
				[
					'status' => 'success',
					'data' => [
						'id' => $returnId
					]
				], self::HTTP_CREATED
			);
		else
			$this->response(
				[
					'message' => 'Bad Request'
				], self::HTTP_BAD_REQUEST
			);
	}

	public function Index_PUT($id)
	{
		$decodedToken = $this->VerifyToken();
		$title = $this->put('title');
		$content = $this->put('content');
		if (!$title && !$content)
			$this->response(
				[
					'message' => 'Must be Title or Content'
				], self::HTTP_BAD_REQUEST
			);
		$article = $this->article->GetArticle($id);
		if ($article == null) $this->NotFoundResponse();
		$isSuccess = false;

		if ($title && $content)
			$isSuccess = $this
				->article
				->Update($id, $title, $content, $decodedToken->user_id);
		else if ($title)
			$isSuccess = $this
				->article
				->UpdateTitle($id, $title, $decodedToken->user_id);
		else if ($content)
			$isSuccess = $this
				->article
				->UpdateContent($id, $content, $decodedToken->user_id);

		if ($isSuccess)
			$this->response(
				[
					'status' => 'success',
					'message' => "Article Updated"
				], self::HTTP_OK
			);
		else
			$this->response(
				[
					'message' => "There is something wrong"
				], self::HTTP_INTERNAL_ERROR
			);
	}


	public function Index_DELETE($id)
	{
		$this->VerifyToken();
		$article = $this->article->GetArticle($id);
		if ($article == null) $this->NotFoundResponse();
		if ($this->article->Delete($id))
			$this->response(
				[
					'status' => 'success',
					'message' => "Successfully Deleted Article"
				], self::HTTP_OK
			);
		else
			$this->response(
				[
					'message' => "There is something wrong"
				], self::HTTP_INTERNAL_ERROR
			);
	}


}
