<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Top10\CabinetBundle\Entity\Rooms
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class rooms
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
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="rooms")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
    */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Roomstype", inversedBy="rooms")
     * @ORM\JoinColumn(name="roomstype_id", referencedColumnName="id")
    */
    protected $roomstype;

	/**
     * @var \Integer
	 * для формы
     */
    public $roomstypeint;

	/**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="rooms")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $author;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=500)
    */ 
    private $name;

	/**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=500)
    */ 
    private $code;

	/**
     * @var string $keywords
     *
     * @ORM\Column(name="keywords", type="string", length=500)
    */ 
    private $keywords;

	/**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=500)
    */ 
    private $description;

	/**
     * @var string $preview
     *
	 * @Assert\NotBlank()
     * @Assert\Length(min=140)
	 * message = "Минимум 140 символов."
     * @ORM\Column(name="preview", type="string", length=2000)
    */
    private $preview;

	/**
     * @var string $characteristics
     *
     * @ORM\Column(name="characteristics", type="string", length=1500)
    */
    private $characteristics;

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
	 * @var boolean $approved
	 *
     * @ORM\Column(name="approved", type="boolean")
     */
    protected $approved;

	/**
     * @ORM\OneToMany(targetEntity="Slides", mappedBy="rooms", cascade={"persist", "remove"})
	 * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $slides;

	/**
     * @ORM\OneToMany(targetEntity="Posts", mappedBy="rooms", cascade={"persist", "remove"})
     */
    protected $posts;

	/**
	 * @ORM\OneToMany(targetEntity="Roomsposts", mappedBy="rooms", cascade={"persist", "remove"})
	*/
	protected $roomsposts;

	/**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="rooms", cascade={"persist", "remove"})
     */
    protected $comments;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="rooms", cascade={"persist", "remove"})
     */
    protected $likes;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="rooms", cascade={"persist", "remove"})
     */
    protected $likescount;

	/**
     * @ORM\OneToMany(targetEntity="Propertiesforegin", mappedBy="rooms", cascade={"persist", "remove"})
     */
    protected $propertiesforegin;

	/**
	 * @ORM\OneToMany(targetEntity="Planforegin", mappedBy="rooms", cascade={"persist", "remove"})
	 */
	protected $planforegin;

	/**
	 * @ORM\OneToMany(targetEntity="Planforeginmeta", mappedBy="rooms", cascade={"persist", "remove"})
	*/
	protected $planforeginmeta;

	/**
	 * @ORM\OneToMany(targetEntity="Anket", mappedBy="rooms", cascade={"persist", "remove"})
	 */
	protected $anket;



	public function __construct()
	{
		$this->comments = new ArrayCollection();
		//$this->setApproved(true);
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
     * Set homes
     *
     * @param \Top10\CabinetBundle\Entity\homes $homes
     * @return rooms
     */
    public function setHomes(\Top10\CabinetBundle\Entity\homes $homes = null)
    {
        $this->homes = $homes;
        return $this;
    }

    /**
     * Get homes
     *
     * @return \Top10\CabinetBundle\Entity\homes
     */
    public function getHomes()
    {
        return $this->homes;
    }

	/**
     * Set roomstype
     *
     * @param \Top10\CabinetBundle\Entity\roomstype $roomstype
     * @return rooms
     */
    public function setRoomstype(\Top10\CabinetBundle\Entity\roomstype $roomstype = null)
    {
        $this->roomstype = $roomstype;
        return $this;
    }

    /**
     * Get roomstype
     *
     * @return \Top10\CabinetBundle\Entity\roomstype
     */
    public function getRoomstype()
    {
        return $this->roomstype;
    }

	/**
     * Set author
     *
     * @param \Top10\CabinetBundle\Entity\User $author
     * @return rooms
     */
    public function setAuthor(\Top10\CabinetBundle\Entity\User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return \Top10\CabinetBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return rooms
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * Set code
     *
     * @param string $code
     * @return rooms
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

	/**
     * Set keywords
     *
     * @param string $keywords
     * @return rooms
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

	/**
     * Set description
     *
     * @param string $description
     * @return rooms
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

	/**
     * Set preview
     *
     * @param string $preview
     * @return rooms
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * Get preview
     *
     * @return string 
     */
    public function getPreview()
    {
        return $this->preview;
    }

	/**
     * Set characteristics
     *
     * @param string $characteristics
     * @return rooms
     */
    public function setCharacteristics($characteristics)
    {
        $this->characteristics = $characteristics;
        return $this;
    }

    /**
     * Get characteristics
     *
     * @return string 
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * Set created
     *
     * @param datetime $created
     * @return rooms
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
     * @return rooms
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
     * Set approved
     *
     * @param boolean $approved
     * @return rooms
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
     * Add slides
     *
     * @param Top10\CabinetBundle\Entity\slides $slides
     * @return rooms
     */
    public function addSlides(\Top10\CabinetBundle\Entity\slides $slides)
    {
        $this->slides[] = $slides;
        return $this;
    }

    /**
     * Remove slides
     *
     * @param <variableType$slides
     */
    public function removeSlides(\Top10\CabinetBundle\Entity\slides $slides)
    {
        $this->slides->removeElement($slides);
    }

    /**
     * Get slides
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSlides()
    {
        return $this->slides;
    }


	/**
     * Add posts
     *
     * @param Top10\CabinetBundle\Entity\posts $posts
     * @return rooms
     */
    public function addPosts(\Top10\CabinetBundle\Entity\posts $posts)
    {
        $this->posts[] = $posts;
        return $this;
    }

    /**
     * Remove posts
     *
     * @param <variableType$posts
     */
    public function removePosts(\Top10\CabinetBundle\Entity\posts $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }




	/**
	 * Add Roomsposts
	 *
	 * @param Top10\CabinetBundle\Entity\Roomsposts $roomsposts
	 * @return rooms
	*/
	public function addRoomsposts(\Top10\CabinetBundle\Entity\Roomsposts $roomsposts)
	{
		$this->roomsposts[] = $roomsposts;
		return $this;
	}

	/**
	 * Remove roomsposts
	 *
	 * @param <variableType$roomsposts
	*/
	public function removeRoomsposts(\Top10\CabinetBundle\Entity\Roomsposts $roomsposts)
	{
		$this->roomsposts->removeElement($roomsposts);
	}

	/**
	 * Get roomsposts
	 *
	 * @return Doctrine\Common\Collections\Collection 
	*/
	public function getRoomsposts()
	{
		return $this->roomsposts;
	}



	/**
     * Add comments
     *
     * @param Top10\CabinetBundle\Entity\comments $comments
     * @return rooms
     */
    public function addComments(\Top10\CabinetBundle\Entity\comments $comments)
    {
        $this->comments[] = $comments;
        return $this;
    }

    /**
     * Remove comments
     *
     * @param <variableType$comments
     */
    public function removeComments(\Top10\CabinetBundle\Entity\comments $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
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

	/**
     * Add Propertiesforegin
     *
     * @param Top10\CabinetBundle\Entity\Propertiesforegin $propertiesforegin
     * @return rooms
     */
    public function addPropertiesforegin(\Top10\CabinetBundle\Entity\Propertiesforegin $propertiesforegin)
    {
        $this->propertiesforegin[] = $propertiesforegin;
        return $this;
    }

    /**
     * Remove propertiesforegin
     *
     * @param <variableType$propertiesforegin
     */
    public function removePropertiesforegin(\Top10\CabinetBundle\Entity\File $propertiesforegin)
    {
        $this->propertiesforegin->removeElement($propertiesforegin);
    }

    /**
     * Get propertiesforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPropertiesforegin()
    {
        return $this->propertiesforegin;
    }

	/**
     * Add Planforegin
     *
     * @param Top10\CabinetBundle\Entity\Planforegin $planforegin
     * @return rooms
     */
    public function addPlanforegin(\Top10\CabinetBundle\Entity\Planforegin $planforegin)
    {
        $this->planforegin[] = $planforegin;
        return $this;
    }

    /**
     * Remove planforegin
     *
     * @param <variableType$planforegin
     */
    public function removePlanforegin(\Top10\CabinetBundle\Entity\Planforegin $planforegin)
    {
        $this->planforegin->removeElement($planforegin);
    }

    /**
     * Get planforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlanforegin()
    {
        return $this->planforegin;
    }

	/**
     * Get planforeginmeta
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlanforeginmeta()
    {
        return $this->planforeginmeta;
    }

	/**
     * Add Anket
     *
     * @param Top10\CabinetBundle\Entity\Anket $anket
     * @return rooms
     */
    public function addAnket(\Top10\CabinetBundle\Entity\Anket $anket)
    {
        $this->anket[] = $anket;
        return $this;
    }

    /**
     * Remove anket
     *
     * @param <variableType$anket
     */
    public function removeAnket(\Top10\CabinetBundle\Entity\Anket $anket)
    {
        $this->anket->removeElement($anket);
    }

    /**
     * Get anket
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAnket()
    {
        return $this->anket;
    }
}