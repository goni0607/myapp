<?php

namespace App\Transformers;

use App\Attachment;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class AttachmentTransformer extends TransformerAbstract
{

    /**
     * Transform single resource.
     *
     * @param  \App\Attachment $attachment
     * @return  array
     */
    public function transform(Attachment $attachment)
    {
        $payload = [
            'id'            => (int) $article->id,
            'title'         => $article->title,
            'content'       => $article->content,
            'content_html'  => markdown($article->content),
            'author'            => [
                'name'  => $article->user->name,
                'email' => $article->user->email,
                'avatar'    => 'http:' . gravatar_profile_url($article->user->email),
            ],
            'tags'          => $article->tags->pluck('slug'),
            'view_count'        => (int) $article->view_count,
            'created'       => $article->create_at->toIso8601String(),
            'attachments'   => (int) $article->attachments->count(),
            'comments'      => (int) $article->comments->count(),
            'links'         => [
                [
                    'rel'   => 'self',
                    'href'  => route('api.v1.articles.show', $article->id),
                ],
                [
                    'rel'   => 'api.v1.articles.attachments.index',
                    'href'  => route('api.v1.articles.attachments.index', $article->id),
                ],
                [
                    'rel'   => 'api.v1.articles.comments.index',
                    'href'  => route('api.v1.articles.comments.index', $article->id),
                ],
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

}
