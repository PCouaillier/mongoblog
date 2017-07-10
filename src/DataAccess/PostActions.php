<?php

namespace MongoBlog\DataAccess;

use \MongoBlog\DataAccess\Entities\Comment;
use \MongoBlog\DataAccess\Entities\Post;
use \MongoDB\Database;

/**
 *
 */
final class PostActions
{
    const POST_COLLECTION = 'post';

    private $client;

    function __construct(Database $db)
    {
        $postCollection = self::POST_COLLECTION;
        $this->client = $db->$postCollection;
    }

    public function fetchPostByTag($tag)
    {
        return new PostIterator($this->client->find(['keywords'=>$tag]));
    }

    public function fetchPost()
    {
        return new PostIterator($this->client->find([],['sort'=>['publishDate.date'=>-1]]));
    }

    public function addPost(Post $post)
    {
        $post = MongoInteraction::object_to_array($post);
        MongoInteraction::cleanId($post);
        $this->client->insertOne($post);
    }

    public function addComment(string $postId, Comment $comment)
    {
        $this->client->updateOne(
            ['_id' => MongoInteraction::toObjectId($postId)],
            ['$push'=>['comments'=>$comment]],
            null);
    }
}
