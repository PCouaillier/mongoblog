<?php

namespace MongoBlog\DataAccess;

use MongoDB\BSON\ObjectID;
use \MongoDB\Client;
use \MongoBlog\DataAccess\Entities\Post;

final class MongoInteraction
{
    private $db;
    private $post;

    public function __construct(string $server, string $dbName)
    {
        $this->db = (new Client($server))->$dbName;
        $this->post = new PostActions($this->db);
    }

    public function addPost(Post $post)
    {
        $this->post->addPost($post);
    }


    public function fetchPostByTag($tag): PostIterator
    {
        return $this->post->fetchPostByTag($tag);
    }

    public function fetchPost(): PostIterator
    {
        return $this->post->fetchPost();
    }

    public static function toObjectId(string $id)
    {
        return new ObjectId($id);
    }

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

    public static function parseKey($key, $classNameLength)
    {
        return ($key[0]==="\0" ? \substr($key, $classNameLength) : $key);
    }

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
}
