<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\tagsforegin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class tagsforegin
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tags", inversedBy="tagsforegin")
     * @ORM\JoinColumn(name="tags_id", referencedColumnName="id")
     */
    protected $tags;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="tagsforegin")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;




    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tags
     *
     * @param Top10\CabinetBundle\Entity\Tags $tags
     * @return tagsforegin
     */
    public function setTags(\Top10\CabinetBundle\Entity\Tags $tags = null)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get tags
     *
     * @return Top10\CabinetBundle\Entity\Tags 
     */
    public function getTags()
    {
        return $this->tags;
    }

	/**
     * Set posts
     *
     * @param Top10\CabinetBundle\Entity\Posts $posts
     * @return tagsforegin
     */
    public function setPosts(\Top10\CabinetBundle\Entity\Posts $posts = null)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * Get posts
     *
     * @return Top10\CabinetBundle\Entity\Posts 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}