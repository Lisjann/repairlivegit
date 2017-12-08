<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Likes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Top10\CabinetBundle\Entity\LikesRepository")
 */
class likes
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
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="likes")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
     */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="likes")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="likes")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;

	/**
     * @ORM\ManyToOne(targetEntity="comments", inversedBy="likes")
     * @ORM\JoinColumn(name="comments_id", referencedColumnName="id")
     */
    protected $comments;

	/**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="likes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $user;

	/**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=50, nullable=true)
     */
    private $ip;
	
	/**
     * @var string $likes
     *
     * @ORM\Column(name="likes", type="integer", length=9)
     */
    private $likes;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
	private $created;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;


	public function __construct()
    {
       

        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }
	
	
	
	
	
	
	
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
     * Set posts
     *
     * @param Top10\CabinetBundle\Entity\Posts $posts
     * @return likes
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

	/**
     * Set homes
     *
     * @param Top10\CabinetBundle\Entity\homes $homes
     * @return likes
     */
    public function setHomes( \Top10\CabinetBundle\Entity\homes $homes = null )
    {
        $this->homes = $homes;
        return $this;
    }

    /**
     * Get homes
     *
     * @return Top10\CabinetBundle\Entity\homes 
     */
    public function getHomes()
    {
        return $this->homes;
    }

	/**
     * Set rooms
     *
     * @param Top10\CabinetBundle\Entity\rooms $rooms
     * @return likes
     */
    public function setRooms( \Top10\CabinetBundle\Entity\rooms $rooms = null )
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
     * Set comments
     *
     * @param Top10\CabinetBundle\Entity\Comments $comments
     * @return likes
     */
    public function setComments(\Top10\CabinetBundle\Entity\Comments $comments = null)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return Top10\CabinetBundle\Entity\Comments 
     */
    public function getComments()
    {
        return $this->comments;
    }

	/**
     * Set user
     *
     * @param \Top10\CabinetBundle\Entity\User $user
     * @return likes
     */
    public function setUser(\Top10\CabinetBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Top10\CabinetBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
     * Set ip
     *
     * @param string $ip
     * @return likes
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

	/**
     * Set likes
     *
     * @param integer $likes
     * @return likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * Get likes
     *
     * @return integer 
     */
    public function getLikes()
    {
        return $this->likes;
    }

	/**
     * Set created
     *
     * @param datetime $created
     * @return likes
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     * @return likes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}