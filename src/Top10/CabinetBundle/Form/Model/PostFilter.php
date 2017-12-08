<?php
namespace Top10\CabinetBundle\Form\Model;
class PostFilter
{
    protected $tags;
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
    public function getTags()
    {
        return $this->tags;
    }
}