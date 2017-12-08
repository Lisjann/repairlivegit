<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Slides
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class slides
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
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="slides")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
    */
    protected $homes;

    
	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="Slides")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
    */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="Slides")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
    */
    protected $posts;

	/**
     * @ORM\ManyToOne(targetEntity="Planforegin", inversedBy="Slides")
     * @ORM\JoinColumn(name="planforegin_id", referencedColumnName="id")
    */
    protected $planforegin;

	/**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=25)
    */ 
    private $type;

	/**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50)
    */ 
    private $code;

	/**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=250)
     */
    private $image;

	/**
     * @var text $description
     *
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

	/**
	 * @var text $link
	 *
	 * @ORM\Column(name="link", type="string", length=250)
	 */
	private $link;

	/**
	* var current integer $current 
	*
	* @ORM\Column(name="current", type="integer", length=1)
	*/
	private $current;

	/**
	 * @var integer $sort
	 *
	 * @ORM\Column(name="sort", type="integer", length=11)
	 * @ORM\OrderBy({"sort" = "ASC"})
	*/
	private $sort;





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
     * @param \Top10\CabinetBundle\Entity\Homes $homes
     * @return slides
     */
    public function setHomes(\Top10\CabinetBundle\Entity\Homes $homes = null)
    {
        $this->homes = $homes;
        return $this;
    }

    /**
     * Get Homes
     *
     * @return \Top10\CabinetBundle\Entity\Homes
    */
    public function getHomes()
    {
        return $this->homes;
    }

    
	/**
     * Set rooms
     *
     * @param \Top10\CabinetBundle\Entity\Rooms $rooms
     * @return slides
     */
    public function setRooms(\Top10\CabinetBundle\Entity\Rooms $rooms = null)
    {
        $this->rooms = $rooms;
        return $this;
    }

    /**
     * Get rooms
     *
     * @return \Top10\CabinetBundle\Entity\Rooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }

	/**
     * Set posts
     *
     * @param \Top10\CabinetBundle\Entity\Posts $posts
     * @return slides
     */
    public function setPosts(\Top10\CabinetBundle\Entity\Posts $posts = null)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * Get posts
     *
     * @return \Top10\CabinetBundle\Entity\Posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

	/**
     * Set planforegin
     *
     * @param \Top10\CabinetBundle\Entity\Planforegin $planforegin
     * @return slides
     */
    public function setPlanforegin(\Top10\CabinetBundle\Entity\Planforegin $planforegin = null)
    {
        $this->planforegin = $planforegin;
        return $this;
    }

    /**
     * Get planforegin
     *
     * @return \Top10\CabinetBundle\Entity\Planforegin
     */
    public function getPlanforegin()
    {
        return $this->planforegin;
    }

	/**
	 * Set type
	 *
	 * @param string $type
	 * @return slides
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string 
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
     * Set code
     *
     * @param string $code
     * @return slides
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
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
     * Set image
     *
     * @param string $image
     * @return slides
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
     * Set description
     *
     * @param string $description
     * @return slides
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
     * Set link
     *
     * @param string $link
     * @return slides
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

	/**
     * Set current
     *
     * @param integer $current
     * @return slides
     */
    public function setCurrent($current)
    {
        $this->current = $current;
        return $this;
    }

    /**
     * Get current
     *
     * @return integer 
     */
    public function getCurrent()
    {
        return $this->current;
    }

	/**
     * Set sort
     *
     * @param integer $sort
     * @return slides
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }
}