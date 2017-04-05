<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentsRequest;

class CommentsController extends Controller
{
    public function __construct()
    {
    		$this->middleware('auth');
    }


    public function store(CommentsRequest $request, \App\Article $article)
    {
        $comment = $article->comments()->create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));
        event(new \App\Events\CommentsEvent($comment));

        flash()->success('작성하신 댓글을 저장했습니다.');

        return redirect(route('articles.show', $article->id).'#comment_'.$comment->id);
    }


    public function update(CommentsRequest $request, \App\Comment $comment)
    {
        $comment->update($request->all());

        return redirect(route('articles.show', $comment->commentable->id).'#comment_'.$comment->id);
    }


    public function destroy(\App\Comment $comment)
    {
        if ($comment->replies()->count() > 0) {
            $comment->delete();
        } else {
            $comment->votes()->delete();
            $comment->forceDelete();
        }

        return response()->json([], 204);
    }


    public function vote(Request $request, \App\Comment $comment)
    {
        $this->validate($request, [
            'vote' => 'required|in:up,down',
        ]);

        if ($comment->votes()->whereUserId($request->user()->id)->exists()) {
            return response()->json(['error' => 'already_voted'], 409);
        }

        $up = $request->input('vote') == 'up' ? true : false;

        $comment->votes()->create([
            'user_id' => $request->user()->id,
            'up' => $up,
            'down' => !$up,
            'voted_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'voted' => $request->input('vote'),
            'value' => $comment->votes()->sum($request->input('vote')),
        ]);
    }
}
