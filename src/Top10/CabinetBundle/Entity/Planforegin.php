<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\planforegin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class planforegin
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
     * @ORM\OneToOne(targetEntity="Planforeginmeta", inversedBy="planforegin")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
    */
    protected $planforeginmeta;

    /**
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="planforegin")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     */
    protected $plan;

	/**
     * @ORM\ManyToOne(targetEntity="Homes", inversedBy="planforegin")
     * @ORM\JoinColumn(name="homes_id", referencedColumnName="id")
     */
    protected $homes;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="planforegin")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="planforegin")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;

	/**
     * @ORM\OneToMany(targetEntity="Slides", mappedBy="planforegin", cascade={"persist", "remove"})
	 * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $slides;

	/**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=100, nullable=true)
     */
    private $value;

	/**
     * @var string $finished
     *
     * @ORM\Column(name="finished", type="string", length=1, nullable=true)
     */
    private $finished;

	/**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string", length=500)
    */
    private $link;

	/**
     * @var float $quantity
     *
     * @ORM\Column(name="quantity", type="float", nullable=true)
     */
    private $quantity;

	/**
     * @var float $price
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

	/**
     * @var float $priceworck
     *
     * @ORM\Column(name="priceworck", type="float", nullable=true)
     */
    private $priceworck;

	/**
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $datebegin;

	/**
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateend;

	/**
	 * @ORM\OneToMany(targetEntity="Propertiesforegin", mappedBy="planforegin", cascade={"persist", "remove"})
	 */
	protected $propertiesforegin;

	/**
	 * @ORM\OneToMany(targetEntity="Materialforegin", mappedBy="planforegin", cascade={"persist", "remove"})
	 */
	protected $materialforegin;

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
     * Get planforeginmeta
     *
     * @return \Top10\CabinetBundle\Entity\planforeginmeta
     */
    public function getPlanforeginmeta()
    {
        return $this->planforeginmeta;
    }

    /**
     * Set plan
     *
     * @param Top10\CabinetBundle\Entity\Plan $plan
     * @return planforegin
     */
    public function setPlan(\Top10\CabinetBundle\Entity\Plan $plan = null)
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * Get plan
     *
     * @return Top10\CabinetBundle\Entity\Plan 
     */
    public function getPlan()
    {
        return $this->plan;
    }

	/**
     * Set homes
     *
     * @param Top10\CabinetBundle\Entity\Homes $homes
     * @return planforegin
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
     * @return planforegin
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
     * @return planforegin
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
     * Add slides
     *
     * @param Top10\CabinetBundle\Entity\slides $slides
     * @return planforegin
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
     * Set value
     *
     * @param string $value
     * @return planforegin
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

	/**
     * Set finished
     *
     * @param string $finished
     * @return planforegin
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
        return $this;
    }

    /**
     * Get finished
     *
     * @return string 
     */
    public function getFinished()
    {
        return $this->finished;
    }

	/**
     * Set name
     *
     * @param string $name
     * @return planforegin
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
     * Set link
     *
     * @param string $link
     * @return planforegin
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
     * Set quantity
     *
     * @param float $quantity
     * @return planforegin
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

	/**
     * Set price
     *
     * @param float $price
     * @return planforegin
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

	/**
     * Set priceworck
     *
     * @param float $priceworck
     * @return planforegin
     */
    public function setPriceworck($priceworck)
    {
        $this->priceworck = $priceworck;
        return $this;
    }

    /**
     * Get priceworck
     *
     * @return float 
     */
    public function getPriceworck()
    {
        return $this->priceworck;
    }

	/**
     * Set datebegin
     *
     * @param datetime $datebegin
     * @return planforegin
     */
    public function setDatebegin($datebegin)
    {
        $this->datebegin = $datebegin;
        return $this;
    }

    /**
     * Get datebegin
     *
     * @return datetime 
     */
    public function getDatebegin()
    {
        return $this->datebegin;
    }

	/**
     * Set dateend
     *
     * @param datetime $dateend
     * @return planforegin
     */
    public function setDateend($dateend)
    {
        $this->dateend = $dateend;
        return $this;
    }

    /**
     * Get dateend
     *
     * @return datetime 
     */
    public function getDateend()
    {
        return $this->dateend;
    }

	/**
     * Add Propertiesforegin
     *
     * @param Top10\CabinetBundle\Entity\Propertiesforegin $propertiesforegin
     * @return planforegin
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
    public function removePropertiesforegin(\Top10\CabinetBundle\Entity\propertiesforegin $propertiesforegin)
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
     * Add Materialforegin
     *
     * @param Top10\CabinetBundle\Entity\Materialforegin $materialforegin
     * @return planforegin
     */
    public function addMaterialforegin(\Top10\CabinetBundle\Entity\Materialforegin $materialforegin)
    {
        $this->materialforegin[] = $materialforegin;
        return $this;
    }

    /**
     * Remove materialforegin
     *
     * @param <variableType$materialforegin
     */
    public function removeMaterialforegin(\Top10\CabinetBundle\Entity\materialforegin $materialforegin)
    {
        $this->materialforegin->removeElement($materialforegin);
    }

    /**
     * Get materialforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMaterialforegin()
    {
        return $this->materialforegin;
    }
}