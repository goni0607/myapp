<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
	public function __construct()
	{

	}


	/* override respondCollection function */
	protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles)
	{
		return $articles->toJson(JSON_PRETTY_PRINT);
	}


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
