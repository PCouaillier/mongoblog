<?php

namespace MongoBlog\DataAccess;

use MongoBlog\DataAccess\Entities\Comment;
use MongoDB\BSON\ObjectID;
use \MongoDB\Client;
use \MongoBlog\DataAccess\Entities\Post;

final class MongoInteraction
{
    /** @var \MongoDB\Database $db */
    private $db;

    /** @var PostActions $post */
    private $post;

    public function __construct(string $server, string $dbName)
    {
        $this->db = (new Client($server))->$dbName;
        $this->post = new PostActions($this->db);
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        $this->post->addPost($post);
    }

    /**
     * @param $tag
     * @return PostIterator
     */
    public function fetchPostByTag($tag): PostIterator
    {
        return $this->post->fetchPostByTag($tag);
    }

    /**
     * @return PostIterator
     */
    public function fetchPost(): PostIterator
    {
        return $this->post->fetchPost();
    }

    /**
     * @param string $id
     * @return ObjectID
     */
    public static function toObjectId(string $id): ObjectID
    {
        return new ObjectId($id);
    }

    /**
     * @param array $array
     */
    public static function cleanId(array &$array)
    {
        if (isset($array['_id'])) {
            $array['_id'] = $array['id'];
            unset($array['id']);
        } else if (isset($array['id'])) {
            $array['_id'] = $array['id'];
            unset($array['id']);
        }
    }

    /**
     * @param $key
     * @param $classNameLength
     * @return bool|string
     */
    public static function parseKey($key, $classNameLength)
    {
        return ($key[0]==="\0" ? \substr($key, $classNameLength) : $key);
    }

    /**
     * @param $obj
     * @return array
     */
    public static function object_to_array($obj): array
    {
        $isObj = \is_object($obj);
        if ($isObj)
        {
            $class = \strlen(\get_class($obj))+2;// +2 because there is two '\0' (null char)
            $obj = (array)$obj;
        }
        $arr = [];
        foreach ($obj as $key => $val) {
            if ($val instanceof \DateTime)
                $val = $val->format(\DateTime::ATOM);
            else if (\is_array($val) || \is_object($val))
                $val = self::object_to_array($val);

            if ($isObj)
                /** @noinspection PhpUndefinedVariableInspection */
                $key = self::parseKey($key,$class);

            if ($key !== 'id')
                $arr[$key] = $val;
            else
                $arr['_id'] = $val;

        }
        return $arr;
    }

    /**
     * @param string $postId
     * @param Comment $comment
     */
    public function addComment(string $postId, Comment $comment) {
        $this->post->addComment($postId, $comment);
    }
}
