<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\planforeginmeta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class planforeginmeta
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
     * @ORM\ManyToOne(targetEntity="Planforegin", inversedBy="planforeginmeta")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
    */
    protected $planforegin;


	/**
     * @ORM\OneToMany(targetEntity="Planforeginmeta", mappedBy="parent")
 	 * @ORM\OrderBy({ "daystobeginchild" = "ASC", "finished" = "DESC", "daystobegin" = "ASC", "sort" = "ASC" })
     */
    private $children;

	/**
     * @ORM\ManyToOne(targetEntity="Planforeginmeta", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id") 
     */
    protected $parent;

	/**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", length=11)
	 * @ORM\OrderBy({"sort" = "ASC"})
     */
	private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="planforeginmeta")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     */
    protected $plan;

	/**
     * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="planforeginmeta")
     * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
     */
    protected $rooms;

	/**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="planforeginmeta")
     * @ORM\JoinColumn(name="posts_id", referencedColumnName="id")
     */
    protected $posts;

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
	 * @var float $pricematerial
	 *
	 * @ORM\Column(name="pricematerial", type="float", nullable=true)
	 */
	private $pricematerial;

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
	 * @var string $daystobegin
	 * дней до начала
	 * @ORM\Column(name="daystobegin", type="integer", length=3)
	 * @ORM\OrderBy({"daystobegin" = "ASC"})
	*/
	private $daystobegin;

	/**
	 * @var string $daystoend
	 * дней до конца
	 * @ORM\Column(name="daystoend", type="integer", length=3)
	*/
	private $daystoend;

	/**
	 * @var string $dayworck
	 * дней  работ
	 * @ORM\Column(name="dayworck", type="integer", length=3)
	*/
	private $dayworck;

		/**
	 * @var string $daystobeginchild
	 * дней до начала
	 * @ORM\Column(name="daystobeginchild", type="integer", length=3)
	*/
	private $daystobeginchild;

	/**
	 * @var string $daystoendchild
	 * дней до конца
	 * @ORM\Column(name="daystoendchild", type="integer", length=3)
	*/
	private $daystoendchild;

	/**
	 * @var string $dayworckchild
	 * дней  работ
	 * @ORM\Column(name="dayworckchild", type="integer", length=3)
	*/
	private $dayworckchild;

	/**
	 * @var string $cofaktor
	 * коофицент сложности подпунктов dayworckchild * complexity
	 * @ORM\Column(name="cofaktor", type="integer", length=3)
	*/
	private $cofaktor;

	/**
	 * @var string $countplanyes
	 * дочерних активных пунктов
	 * @ORM\Column(name="countplanyes", type="integer", length=3)
	*/
	private $countplanyes;

	/**
	 * @var string $countfinished
	 * дочерних активных пунктов
	 * @ORM\Column(name="countfinished", type="integer", length=3)
	*/
	private $countfinished;

	/**
	 * @var string $sumprice
	 * дочерних активных пунктов
	 * @ORM\Column(name="sumprice", type="integer", length=9)
	*/
	private $sumprice;

		/**
	 * @var string $sumpricefinished
	 * дочерних активных пунктов
	 * @ORM\Column(name="sumpricefinished", type="integer", length=9)
	*/
	private $sumpricefinished;

	/**
	 * @ORM\OneToMany(targetEntity="Propertiesforegin", mappedBy="planforeginmeta", cascade={"persist", "remove"})
	 */
	protected $propertiesforegin;

	public function __construct()
    {
    	$this->children = new ArrayCollection();
    	// your own logic
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
     * Get planforegin
     *
     * @return \Top10\CabinetBundle\Entity\planforegin
     */
    public function getPlanforegin()
    {
        return $this->planforegin;
    }

	public function getChildren()
    {
        return $this->children;
    }

	/**
	 * Get parent
	 *
	 * @return Top10\CabinetBundle\Entity\Planforeginmeta 
	 */
	public function getParent()
	{
		return $this->parent;
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
     * Get rooms
     *
     * @return Top10\CabinetBundle\Entity\Rooms 
     */
    public function getRooms()
    {
        return $this->rooms;
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
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
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
	 * Get pricematerial
	 *
	 * @return float 
	*/
	public function getPricematerial()
	{
		return $this->pricematerial;
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
     * Get datebegin
     *
     * @return datetime 
     */
    public function getDatebegin()
    {
        return $this->datebegin;
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
     * Get propertiesforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPropertiesforegin()
    {
        return $this->propertiesforegin;
    }

	/**
	 * Get daystobegin
	 *
	 * @return integer 
	 */
	public function getDaystobegin()
	{
		return $this->daystobegin;
	}

	/**
	 * Get daystoend
	 *
	 * @return integer 
	 */
	public function getDaystoend()
	{
		return $this->daystoend;
	}

	/**
	 * Get dayworck
	 *
	 * @return integer 
	 */
	public function getDayworck()
	{
		return $this->dayworck;
	}

		/**
	 * Get daystobeginchild
	 *
	 * @return integer 
	 */
	public function getDaystobeginchild()
	{
		return $this->daystobeginchild;
	}

	/**
	 * Get daystoendchild
	 *
	 * @return integer 
	 */
	public function getDaystoendchild()
	{
		return $this->daystoendchild;
	}

	/**
	 * Get dayworckchild
	 *
	 * @return integer 
	 */
	public function getDayworckchild()
	{
		return $this->dayworckchild;
	}

	/**
	 * Get cofaktor
	 *
	 * @return integer 
	 */
	public function getСofaktor()
	{
		return $this->cofaktor;
	}

	/**
	 * Get countplanyes
	 *
	 * @return integer 
	 */
	public function getCountplanyes()
	{
		return $this->countplanyes;
	}

	/**
	 * Get countfinished
	 *
	 * @return integer 
	 */
	public function getCountfinished()
	{
		return $this->countfinished;
	}

	/**
	 * Get sumprice
	 *
	 * @return integer 
	 */
	public function getSumprice()
	{
		return $this->sumprice;
	}

		/**
	 * Get sumpricefinished
	 *
	 * @return integer 
	 */
	public function getSumpricefinished()
	{
		return $this->sumpricefinished;
	}
}