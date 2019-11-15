<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Top10\CabinetBundle\Entity\Posts
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class posts
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
     * @ORM\ManyToOne(targetEntity="user", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $author;

	/**
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="posts")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
    */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="posts")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
    */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Popposts", inversedBy="posts")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
    */
    protected $popposts;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=500)
    */ 
    private $name;

	/**
     * @var string $code
     *
	 * @Assert\NotBlank()
     * @ORM\Column(name="code", type="string", length=500)
    */ 
    private $code;

	/**
     * @var string $keywords
     *
     * @ORM\Column(name="title", type="string", length=500)
    */ 
    private $title;

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
     * @ORM\Column(name="preview", type="string", length=1000)
    */
    private $preview;

	/**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=500)
     */
    private $image;

	/**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

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
	 * @var boolean $interesting
	 *
     * @ORM\Column(name="interesting", type="boolean")
     */
    protected $interesting;

	/**
	 * @var boolean $useful
	 *
     * @ORM\Column(name="useful", type="boolean")
     */
    protected $useful;

	/**
     * @var boolean $mainBig
     *
     * @ORM\Column(name="mainBig", type="boolean")
     */
    private $mainBig;

	/**
     * @var boolean $mainSmall
     *
     * @ORM\Column(name="mainSmall", type="boolean")
     */
    private $mainSmall;

	/**
	 * @var boolean $approved
	 *
     * @ORM\Column(name="approved", type="boolean")
     */
    protected $approved;

	/**
     * @ORM\OneToMany(targetEntity="Slides", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $slides;

	/**
     * @ORM\OneToMany(targetEntity="File", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $files;

	/**
     * @ORM\OneToMany(targetEntity="Tagsforegin", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $tagsforegin;

	/**
	 * @ORM\OneToMany(targetEntity="Roomsposts", mappedBy="posts", cascade={"persist", "remove"})
	*/
	protected $roomsposts;

	/**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $comments;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $likes;

	/**
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="posts", cascade={"persist", "remove"})
     */
    protected $likescount;



	public function __construct()
    {
		$this->files = new ArrayCollection();
		$this->comments = new ArrayCollection();
		//$this->tagsforegin = new ArrayCollection();
		$this->roomsposts = new ArrayCollection();

		$this->setApproved(true);

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
     * Set author
     *
     * @param \Top10\CabinetBundle\Entity\User $author
     * @return posts
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
     * Set homes
     *
     * @param \Top10\CabinetBundle\Entity\Homes $homes
     * @return posts
     */
    public function setHomes(\Top10\CabinetBundle\Entity\Homes $homes = null)
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
     * Set rooms
     *
     * @param \Top10\CabinetBundle\Entity\Rooms $rooms
     * @return posts
     */
    public function setRooms(\Top10\CabinetBundle\Entity\Rooms $rooms = null)
    {
        $this->rooms = $rooms;
        return $this;
    }

	/**
     * Get rooms
     *
     * @return \Top10\CabinetBundle\Entity\rooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Get popposts
     *
     * @return \Top10\CabinetBundle\Entity\popposts
     */
    public function getPopposts()
    {
        return $this->popposts;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return posts
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
     * @return posts
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

	/**
     * Set title
     *
     * @param string $title
     * @return posts
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
     * Set keywords
     *
     * @param string $keywords
     * @return posts
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
     * @return posts
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
     * @return posts
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
	 * Вывести первые 30 слов Превью, если нет Превью вывести первые 100 слов Контента
	 *
	 * 
	 * @return mixed
	 */
	public function getPreviewFirstWords( $countword = 30 )
	{
		if ( $this->getPreview() )
			$preview = $this->getPreview();
		elseif ( $this->getContent() )
			$preview = strip_tags( $this->getContent() );
		else
			return '...'; 

		//$previewArr = str_word_count($preview, 1); 
		$previewArr = explode(" ", $preview);

		$previewFW = null;
		foreach( $previewArr as $key => $item ){ 
			if($key >= $countword)
				break;
			$previewFW = $previewFW . ' ' . $item;
		} 
		 
		return $previewFW . '...';
	}


	/**
     * Set image
     *
     * @param string $image
     * @return posts
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

	/**
     * Set content
     *
     * @param text $content
     * @return posts
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
     * Set created
     *
     * @param datetime $created
     * @return posts
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
     * @return posts
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
     * Set interesting
     *
     * @param boolean $interesting
     * @return post
     */
    public function setInteresting($interesting)
    {
        $this->interesting = $interesting;
        return $this;
    }

    /**
     * Get interesting
     *
     * @return boolean 
     */
    public function getInteresting()
    {
        return $this->interesting;
    }

	/**
     * Set useful
     *
     * @param boolean $useful
     * @return post
     */
    public function setUseful($useful)
    {
        $this->useful = $useful;
        return $this;
    }

    /**
     * Get useful
     *
     * @return boolean 
     */
    public function getUseful()
    {
        return $this->useful;
    }

	/**
     * Set mainBig
     *
     * @param boolean $mainBig
     * @return posts
     */
    public function setMainBig($mainBig)
    {
        $this->mainBig = $mainBig;
        return $this;
    }

    /**
     * Get mainBig
     *
     * @return boolean 
     */
    public function getMainBig()
    {
        return $this->mainBig;
    }

	/**
     * Set mainSmall
     *
     * @param boolean $mainSmall
     * @return posts
     */
    public function setMainSmall($mainSmall)
    {
        $this->mainSmall = $mainSmall;
        return $this;
    }

    /**
     * Get mainSmall
     *
     * @return boolean 
     */
    public function getMainSmall()
    {
        return $this->mainSmall;
    }

	/**
     * Set approved
     *
     * @param boolean $approved
     * @return post
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
     * @return posts
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
     * Add files
     *
     * @param Top10\CabinetBundle\Entity\File $files
     * @return posts
     */
    public function addFile(\Top10\CabinetBundle\Entity\File $files)
    {
        $this->files[] = $files;
        return $this;
    }
	

    /**
     * Remove files
     *
     * @param <variableType$files
     */
    public function removeFile(\Top10\CabinetBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }




	/**
     * Add Tagsforegin
     *
     * @param Top10\CabinetBundle\Entity\Tagsforegin $tagsforegin
     * @return posts
     */
    public function addTagsforegin(\Top10\CabinetBundle\Entity\Tagsforegin $tagsforegin)
    {
        $this->tagsforegin[] = $tagsforegin;
        return $this;
    }

    /**
     * Remove tagsforegin
     *
     * @param <variableType$tagsforegin
     */
    public function removeTagsforegin(\Top10\CabinetBundle\Entity\Tagsforegin $tagsforegin)
    {
        $this->tagsforegin->removeElement($tagsforegin);
    }

    /**
     * Get tagsforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTagsforegin()
    {
        return $this->tagsforegin;
    }




	/**
	 * Add Roomsposts
	 *
	 * @param Top10\CabinetBundle\Entity\Roomsposts $roomsposts
	 * @return posts
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
     * @return posts
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
}