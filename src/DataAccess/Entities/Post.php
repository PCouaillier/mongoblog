<?php

namespace MongoBlog\DataAccess\Entities;

/**
 * Class Post
 * @package MongoBlog\DataAccess\Entities
 */
final class Post
{
    private $id;
    private $author;
    private $publishDate;
    private $comments;
    private $keywords;

    /** @var string $content */
    public $content;

    /**
     * Post constructor.
     * @param $id
     * @param $author
     * @param $publishDate
     * @param $content
     * @param array $keywords
     * @param null $comments
     * @throws \Exception
     */
    public function __construct($id, $author, $publishDate, $content, array $keywords, $comments = null)
    {
        if($author == null || $publishDate == null || $content == null || $keywords === null || ($comments!==null && !is_array($comments)))
        {
            throw new \Exception();
        }

        if($comments === null)
            $comments = [];
        $this->id = $id;
        $this->publishDate = $publishDate;
        $this->author = $author;
        $this->content = $content;
        $this->keywords = $keywords;
        $this->comments = $comments;
    }

    /**
     * @param $author
     * @param $content
     * @param $keywords
     * @return Post
     */
    public static function publish($author, $content, $keywords): self
    {
        return new Post(null, $author, new \DateTime(), $content, $keywords, []);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function author(): string
    {
        return $this->author;
    }

    /**
     * @return \DateTime
     */
    public function publishDate(): \DateTime
    {
        return $this->publishDate;
    }

    /**
     * @return array
     */
    public function keywords(): array
    {
        return $this->keywords;
    }

    /**
     * @return array
     */
    public function comments(): array
    {
        return $this->comments;
    }
}
