<?php
namespace Top10\CabinetBundle\Form\Model;

class CommentsResponsePost
{
    protected $authorResponse;

    protected $contentResponse;

    protected $parentResponse;


    public function setAuthorResponse($authorResponse)
    {
        $this->authorResponse = $authorResponse;
    }
    public function getAuthorResponse()
    {
        return $this->authorResponse;
    }

	 public function setContentResponse($contentResponse)
    {
        $this->content = $contentResponse;
    }
    public function getContentResponse()
    {
        return $this->contentResponse;
    }

	 public function setParentResponse($parentResponse)
    {
        $this->parentResponse = $parentResponse;
    }
    public function getParentResponse()
    {
        return $this->parentResponse;
    }
}