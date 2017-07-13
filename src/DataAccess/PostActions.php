<?php

namespace MongoBlog\DataAccess;

use \MongoBlog\DataAccess\Entities\Comment;
use \MongoBlog\DataAccess\Entities\Post;
use \MongoDB\Database;

/**
 * Class PostActions
 * @package MongoBlog\DataAccess
 */
final class PostActions
{
    const POST_COLLECTION = 'post';

    private $client;

    /**
     * PostActions constructor.
     * @param Database $db
     */
    function __construct(Database $db)
    {
        $postCollection = self::POST_COLLECTION;
        $this->client = $db->$postCollection;
    }

    /**
     * @param $tag
     * @return PostIterator
     */
    public function fetchPostByTag($tag)
    {
        return new PostIterator($this->client->find(['keywords'=>$tag]));
    }

    /**
     * @return PostIterator
     */
    public function fetchPost()
    {
        return new PostIterator($this->client->find([],['sort'=>['publishDate.date'=>-1]]));
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        $post = MongoInteraction::object_to_array($post);
        MongoInteraction::cleanId($post);
        $this->client->insertOne($post);
    }

    /**
     * @param string $postId
     * @param Comment $comment
     */
    public function addComment(string $postId, Comment $comment)
    {
        $this->client->updateOne(
            ['_id' => MongoInteraction::toObjectId($postId)],
            ['$push'=>['comments'=>$comment]],
            null);
    }
}
