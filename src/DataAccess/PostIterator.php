<?php

namespace MongoBlog\DataAccess;

use \MongoBlog\DataAccess\Entities\Post;
use \MongoBlog\DataAccess\Entities\Comment;

final class PostIterator implements \Iterator
{
    private $posts;

    function __construct($posts)
    {
        $this->posts = new \IteratorIterator($posts);
        $this->posts->rewind();
    }

    function rewind()
    {
        $this->posts->rewind();
    }

    function current(): Post
    {
        return self::convert($this->posts->current());
    }

    /**
     * @return mixed
     */
    function key()
    {
        return $this->posts->key();
    }

    /**
     * @return void
     */
    function next()
    {
        $this->posts->next();
    }

    /**
     * @return bool
     */
    function valid()
    {
        return $this->posts->valid();
    }

    /**
     * @param $post
     * @return Post
     */
    private static function convert(array $post): Post
    {
        $comments = $post['comments'];
        if ($comments && \is_array($comments))
            \array_walk($comments, function(&$comment) {
                $comment = new Comment((string)$comment['_id'],
                    $comment['author'],
                    new \DateTime($comment['publishDate']),
                    $comment['content']);
            });
        else
            $comments = [];
        return new Post((string)$post['_id'],
            $post['author'],
            new \DateTime($post['publishDate']),
            $post['content'],
            $post['keywords']?? [],
            $comments
        );
    }
}
