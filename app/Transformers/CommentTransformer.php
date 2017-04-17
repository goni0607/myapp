<?php

namespace App\Transformers;

use App\Comment;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class CommentTransformer extends TransformerAbstract
{

    /**
     * Transform single resource.
     *
     * @param  \App\Comment $comment
     * @return  array
     */
    public function transform(Comment $comment)
    {
        $payload = [
            'id' => (int) $comment->id,
            'content' => $comment->content,
            'content_html' => markdown($comment->content),
            'author' => [
                'name' => $comment->user->name,
                'email' => $comment->user->email,
                'avatar' => 'http:' . gravatar_profile_url($comment->user->email),
            ],
            'created' => $comment->created_at->toIso8601String(),
            'link' => [
                 'rel' => 'self',
                 'href' => route('api.v1.comments.show', $comment->id),
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

}
