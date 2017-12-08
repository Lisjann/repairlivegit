<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\roomsposts
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class roomsposts
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
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="roomsposts")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="roomsposts")
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
     * Set rooms
     *
     * @param Top10\CabinetBundle\Entity\Rooms $rooms
     * @return roomsposts
     */
    public function setRooms(\Top10\CabinetBundle\Entity\Rooms $rooms = null)
    {
        $this->rooms = $rooms;
        return $this;
    }

    /**
     * Get rooms
     *
     * @return Top10\CabinetBundle\Entity\Rooms 
     */
    public function getRooms()
    {
        return $this->rooms;
    }

	/**
     * Set posts
     *
     * @param Top10\CabinetBundle\Entity\Posts $posts
     * @return roomsposts
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