<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\propertiesforegin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class propertiesforegin
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
     * @ORM\ManyToOne(targetEntity="Properties", inversedBy="propertiesforegin")
     * @ORM\JoinColumn(name="properties_id", referencedColumnName="id")
     */
    protected $properties;

	/**
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="propertiesforegin")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
     */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="propertiesforegin")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="propertiesforegin")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;

	/**
	 * @ORM\ManyToOne(targetEntity="Planforegin", inversedBy="propertiesforegin")
	 * @ORM\JoinColumn(name="planforegin_id", referencedColumnName="id")
	 */
	protected $planforegin;

	/**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

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
     * Set properties
     *
     * @param Top10\CabinetBundle\Entity\Properties $properties
     * @return propertiesforegin
     */
    public function setProperties(\Top10\CabinetBundle\Entity\Properties $properties = null)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * Get properties
     *
     * @return Top10\CabinetBundle\Entity\Properties 
     */
    public function getProperties()
    {
        return $this->properties;
    }

	/**
     * Set homes
     *
     * @param Top10\CabinetBundle\Entity\Homes $homes
     * @return propertiesforegin
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
     * @return propertiesforegin
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
     * @return propertiesforegin
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
     * Set planforegin
     *
     * @param Top10\CabinetBundle\Entity\Homes $planforegin
     * @return propertiesforegin
     */
    public function setPlanforegin(\Top10\CabinetBundle\Entity\Planforegin $planforegin = null)
    {
        $this->planforegin = $planforegin;
        return $this;
    }

    /**
     * Get planforegin
     *
     * @return Top10\CabinetBundle\Entity\Planforegin 
    */
    public function getPlanforegin()
    {
        return $this->planforegin;
    }

	/**
     * Set value
     *
     * @param string $value
     * @return propertiesforegin
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

}