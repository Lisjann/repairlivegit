<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

 /**
 * Top10\CabinetBundle\Entity\Comments
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Top10\CabinetBundle\Entity\CommentsRepository")
 */
class comments
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
     * @ORM\OneToMany(targetEntity="comments", mappedBy="parent")
	 * @ORM\OrderBy({"created" = "DESC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="comments", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")	 
     */
    protected $parent;

	/**
     * @var \Integer
	 * для формы
     */
    public $parentint;

	/**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

	/**
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="comments")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
     */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="comments")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="comments")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;

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

	/**
     * @var string $author
     *
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

	/**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


	/**
	 * @var boolean $approved
	 *
     * @ORM\Column(name="approved", type="boolean")
     */
    protected $approved;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="comments", cascade={"persist", "remove"})
     */
    protected $likes;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="comments", cascade={"persist", "remove"})
     */
    protected $likescount;





	public function __construct()
	{
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());

		$this->setApproved(true);

		$this->children = new ArrayCollection();
		$this->likes = new ArrayCollection();
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
     * Set parent
     *
     * @param Top10\CabinetBundle\Entity\Comments $parent
     * @return Comments
     */
    public function setParent( Comments $parent = null )
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Top10\CabinetBundle\Entity\Comments 
     */
    public function getParent()
    {
        return $this->parent;
    }


	public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set user
     *
     * @param \Top10\CabinetBundle\Entity\User $user
     * @return Comments
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
     * Set homes
     *
     * @param Top10\CabinetBundle\Entity\Homes $homes
     * @return Comments
     */
    public function setHomes(\Top10\CabinetBundle\Entity\Homes $homes = null)
    {
        $this->homes = $homes;
        return $this;
    }

    /**
     * Get homes
     *
     * @return Top10\CabinetBundle\Entity\Homes 
     */
    public function getHomes()
    {
        return $this->homes;
    }

	/**
     * Set rooms
     *
     * @param Top10\CabinetBundle\Entity\Rooms $rooms
     * @return Comments
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
     * @return Comments
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
     * Set created
     *
     * @param datetime $created
     * @return Comments
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
     * @return Comments
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

	/**
     * Set author
     *
     * @param string $author
     * @return Comments
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
	

	/**
     * Set content
     *
     * @param text $content
     * @return Comments
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

	/**
     * Set approved
     *
     * @param boolean $approved
     * @return Comments
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }



	/**
     * Add likes
     *
     * @param Top10\CabinetBundle\Entity\likes $likes
     * @return likes
     */
    public function addLikes(\Top10\CabinetBundle\Entity\likes $likes)
    {
        $this->likes[] = $likes;
        return $this;
    }

    /**
     * Remove likes
     *
     * @param <variableType$likes
     */
    public function removeLikes(\Top10\CabinetBundle\Entity\likes $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLikes()
    {
        return $this->likes;
    }





	/**
     * Add likescount
     *
     * @param Top10\CabinetBundle\Entity\likes $likescount
     * @return likescount
     */
    public function addLikescount(\Top10\CabinetBundle\Entity\likes $likescount)
    {
        $this->likescount[] = $likescount;
        return $this;
    }

    /**
     * Remove likescount
     *
     * @param <variableType$likescount
     */
    public function removeLikescount(\Top10\CabinetBundle\Entity\likes $likescount)
    {
        $this->likescount->removeElement($likescount);
    }

    /**
     * Get likescount
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLikescount()
    {
        return $this->likescount;
    }






	public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('author', new NotBlank(array(
            'message' => 'You must enter your name'
        )));
        $metadata->addPropertyConstraint('content', new NotBlank(array(
            'message' => 'You must enter a comment'
        )));
    }





}