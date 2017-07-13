<?php
namespace MongoBlog\DataAccess\Entities;

/**
 * Class Comment
 * @package MongoBlog\DataAccess\Entities
 */
final class Comment
{
    /**@var string */
    private $id;

    /**@var string */
    private $author;

    /**@var \DateTime */
    private $publishDate;

    /**@var string */
    public $content;

    /**
     * Comment constructor.
     * @param string $id
     * @param string $author
     * @param \DateTime $publishDate
     * @param string $content
     * @throws \Exception
     */
    public function __construct(string $id, string $author, \DateTime $publishDate, string $content)
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

    /**
     * @param $author
     * @param $content
     * @return Comment
     */
    public static function publish(string $author, string $content): self
    {
        return new Comment(null, $author, new \DateTime(), $content);
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

}
