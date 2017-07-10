<?php
namespace MongoBlog\DataAccess\Entities;
/**
 *
 */
final class Comment
{
    private $id;
    private $author;
    private $publishDate;
    public $content;

    public function __construct($id, $author, $publishDate, $content)
    {
        if($author === null || $publishDate === null || $content === null)
        {
            throw new \Exception('null parameter');
        }
        $this->id = $id;
        $this->publishDate = $publishDate;
        $this->author = $author;
        $this->content = $content;
    }

    public static function publish($author, $content): self
    {
        return new Comment(null, $author, new \DateTime(), $content);
    }

    public function id()
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

}
