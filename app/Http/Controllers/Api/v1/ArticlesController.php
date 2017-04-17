<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
	public function __construct()
	{
		parent::__construct();
		$this->middleware = [];
		// $this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);
		$this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
	}


	/* override respondCollection function */
	protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles)
	{
		//return $articles->toJson(JSON_PRETTY_PRINT);
		//return (new \App\Transformers\ArticleTransformerBasic)->withPagination($articles);
		return json()->withPagination(
			$articles,
			new \App\Transformers\ArticleTransformer
		);
	}

	/*
	protected function respondInstance(\App\Article $article, $comments)
	{
		return (new \App\Transformers\ArticleTransformerBasic)->withItem($article);
	}
	*/

	/* override respondCreated function */
	protected function respondCreated(\App\Article $article)
	{
		return response()->json(
			['success' => 'created'],
			201,
			['Location' => route('api.v1.articles.show', $article->id)],
			JSON_PRETTY_PRINT
		);
	}


	public function tags()
	{
		return \App\Tag::all();
	}
}
