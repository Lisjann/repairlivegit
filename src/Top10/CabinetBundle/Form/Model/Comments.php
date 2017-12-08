<?php
namespace Top10\CabinetBundle\Form\Model;

class Comments
{
    protected $author;

    protected $content;

    protected $parent;

    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getAuthor()
    {
        return $this->author;
    }

	 public function setContent($content)
    {
        $this->content = $content;
    }
    public function getContent()
    {
        return $this->content;
    }

	 public function setParent($parent)
    {
        $this->parent = $parent;
    }
    public function getParent()
    {
        return $this->parent;
    }
}