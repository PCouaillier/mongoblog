<?php

namespace MongoBlog\DataAccess\Entities;

/**
 *
 */
final class Post
{
    private $id;
    private $author;
    private $publishDate;
    private $comments;
    private $keywords;
    public $content;


    public function __construct($id, $author, $publishDate, $content, $keywords, $comments = null)
    {
        if($author == null || $publishDate == null || $content == null || $keywords === null)
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

    public static function publish($author, $content, $keywords): self
    {
        return new Post(null, $author, new \DateTime(), $content, $keywords, []);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function publishDate(): \DateTime
    {
        return $this->publishDate;
    }

    public function keywords(): array
    {
        return $this->keywords;
    }

    public function comments(): array
    {
        return $this->comments;
    }
}
