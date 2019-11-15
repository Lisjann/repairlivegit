<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Plan
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class plan
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
	 * @ORM\OneToMany(targetEntity="Plan", mappedBy="parent")
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	private $children;

	/**
	 * @ORM\ManyToOne(targetEntity="Plan", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")	 
	 */
	protected $parent;

	/**
	* @var \Integer
	 * для формы
	*/
	public $parentint;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

	/**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;

	/**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;


	/**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255)
    */
    private $image;

	/**
     * @var string $preview
     *
     * @ORM\Column(name="preview", type="string", length=800)
    */
    private $preview;

	/**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
	private $content;

	/**
     * @var string $doyouneed
     * Нужно ли вам включать этот пункт в сой ремонт
     * @ORM\Column(name="doyouneed", type="string", length=255)
    */
    private $doyouneed;

	/**
	 * @var string $plus
	 * Плюсы
	 * @ORM\Column(name="plus", type="string", length=255)
	*/
	private $plus;

	/**
	 * @var string $minus
	 * Плюсы
	 * @ORM\Column(name="minus", type="string", length=255)
	*/
	private $minus;

	/**
     * @var string $externallinks
     * Внешние ссылки
     * @ORM\Column(name="externallinks", type="string", length=800)
    */
	private $externallinks;

	/**
     * @var string $videolinks
     *
     * @ORM\Column(name="videolinks", type="string", length=500)
    */
	private $videolinks;

	/**
     * @var string $pricematerial
     *
     * @ORM\Column(name="pricematerial", type="integer", length=9)
    */
	private $pricematerial;
	
	/**
     * @var text $pricematerialmeasure
     *
     * @ORM\Column(name="pricematerialmeasure", type="string", length=20)
     */
    private $pricematerialmeasure;

	/**
	 * @var string $priceworck
	 *
	 * @ORM\Column(name="priceworck", type="integer", length=9)
	*/
	private $priceworck;

	/**
	 * @var text $priceworckmeasure
	 *
	 * @ORM\Column(name="priceworckmeasure", type="string", length=20)
	 */
	private $priceworckmeasure;

	/**
     * @var string $complexity
     * Сложность работ оценка от 1 до 5
     * @ORM\Column(name="complexity", type="integer", length=1)
    */
	private $complexity;

	/**
     * @var string $worcktime
     * Время на работу в днях
     * @ORM\Column(name="worcktime", type="float", length=3)
    */
	private $worcktime;

	/**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", length=11)
	 * @ORM\OrderBy({"sort" = "ASC"})
     */
	private $sort;

	/**
     * @ORM\OneToMany(targetEntity="Planforegin", mappedBy="plan", cascade={"persist", "remove"})
     */
    protected $planforegin;

	/**
     * @ORM\ManyToOne(targetEntity="Planforeginmeta", inversedBy="plan")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
    */ 
    protected $planforeginmeta;

	/**
     * @ORM\OneToMany(targetEntity="Plantypelist", mappedBy="plan", cascade={"persist", "remove"})
     */
    protected $plantypelist;

	/**
	 * @ORM\OneToMany(targetEntity="Planproperties", mappedBy="plan", cascade={"persist", "remove"})
	*/
	protected $planproperties;

	/**
     * @ORM\ManyToOne(targetEntity="Popplan", inversedBy="plan")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
    */
    protected $popplan;


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

	public function getChildren()
    {
        return $this->children;
    }

	/**
	 * Set parent
	 *
	 * @param Top10\CabinetBundle\Entity\Plan $parent
	 * @return Plan
	 */
	public function setParent( Plan $parent = null )
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return Top10\CabinetBundle\Entity\Plan 
	 */
	public function getParent()
	{
		return $this->parent;
	}

    /**
     * Set name
     *
     * @param string $name
     * @return plan
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
     * @return plan
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
     * Set type
     *
     * @param string $type
     * @return plan
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
     * Add Planforegin
     *
     * @param Top10\CabinetBundle\Entity\Planforegin $planforegin
     * @return plan
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
    public function removePlanforegin(\Top10\CabinetBundle\Entity\planforegin $planforegin)
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
     * Add Plantypelist
     *
     * @param Top10\CabinetBundle\Entity\Plantypelist $plantypelist
     * @return plan
     */
    public function addPlantypelist(\Top10\CabinetBundle\Entity\Plantypelist $plantypelist)
    {
        $this->plantypelist[] = $plantypelist;
        return $this;
    }

    /**
     * Remove plantypelist
     *
     * @param <variableType$plantypelist
     */
    public function removePlantypelist(\Top10\CabinetBundle\Entity\plantypelist $plantypelist)
    {
        $this->plantypelist->removeElement($plantypelist);
    }

    /**
     * Get plantypelist
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlantypelist()
    {
        return $this->plantypelist;
    }

	/**
     * Add Planproperties
     *
     * @param Top10\CabinetBundle\Entity\Planproperties $planproperties
     * @return plan
     */
    public function addPlanproperties(\Top10\CabinetBundle\Entity\Planproperties $planproperties)
    {
        $this->planproperties[] = $planproperties;
        return $this;
    }

    /**
     * Remove planproperties
     *
     * @param <variableType$planproperties
     */
    public function removePlanproperties(\Top10\CabinetBundle\Entity\planproperties $planproperties)
    {
        $this->planproperties->removeElement($planproperties);
    }

    /**
     * Get planproperties
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlanproperties()
    {
        return $this->planproperties;
    }

	/**
     * Set image
     *
     * @param string $image
     * @return plan
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
     * Set preview
     *
     * @param string $preview
     * @return plan
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
     * Set content
     *
     * @param text $content
     * @return plan
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
	 * Set doyouneed
	 *
	 * @param string $doyouneed
	 * @return plan
	 */
	public function setDoyouneed($doyouneed)
	{
		$this->doyouneed = $doyouneed;
		return $this;
	}

	/**
	 * Get doyouneed
	 *
	 * @return string 
	 */
	public function getDoyouneed()
	{
		return $this->doyouneed;
	}

	/**
	 * Set plus
	 *
	 * @param string $plus
	 * @return plan
	 */
	public function setPlus($plus)
	{
		$this->plus = $plus;
		return $this;
	}

	/**
	 * Get plus
	 *
	 * @return string 
	 */
	public function getPlus()
	{
		return $this->plus;
	}

	/**
	 * Set minus
	 *
	 * @param string $minus
	 * @return minus
	 */
	public function setMinus($minus)
	{
		$this->minus = $minus;
		return $this;
	}

	/**
	 * Get minus
	 *
	 * @return string 
	 */
	public function getMinus()
	{
		return $this->minus;
	}

	/**
     * Set externallinks
     *
     * @param string $externallinks
     * @return plan
     */
    public function setExternallinks($externallinks)
    {
        $this->externallinks = $externallinks;
        return $this;
    }

    /**
     * Get externallinks
     *
     * @return string 
     */
    public function getExternallinks()
    {
        return $this->externallinks;
    }

	/**
     * Set videolinks
     *
     * @param string $videolinks
     * @return plan
     */
    public function setVideolinks($videolinks)
    {
        $this->videolinks = $videolinks;
        return $this;
    }

	/**
	 * Get videolinks
	 *
	 * @return string 
	 */
	public function getVideolinks()
	{
		return $this->videolinks;
	}

	/**
	 * Set pricematerial
	 *
	 * @param string $pricematerial
	 * @return plan
	 */
	public function setPricematerial($pricematerial)
	{
		$this->pricematerial = $pricematerial;
		return $this;
	}

	/**
	 * Get pricematerial
	 *
	 * @return string 
	 */
	public function getPricematerial()
	{
		return $this->pricematerial;
	}

	/**
	 * Set pricematerialmeasure
	 *
	 * @param string $pricematerialmeasure
	 * @return plan
	 */
	public function setPricematerialmeasure($pricematerialmeasure)
	{
		$this->pricematerialmeasure = $pricematerialmeasure;
		return $this;
	}

    /**
     * Get pricematerialmeasure
     *
     * @return string 
     */
    public function getPricematerialmeasure()
    {
        return $this->pricematerialmeasure;
    }

	/**
	 * Set priceworck
	 *
	 * @param string $priceworck
	 * @return plan
	 */
	public function setPriceworck($priceworck)
	{
		$this->priceworck = $priceworck;
		return $this;
	}

	/**
	 * Get priceworck
	 *
	 * @return string 
	 */
	public function getPriceworck()
	{
		return $this->priceworck;
	}

		/**
	 * Set priceworckmeasure
	 *
	 * @param string $priceworckmeasure
	 * @return plan
	 */
	public function setPriceworckmeasure($priceworckmeasure)
	{
		$this->priceworckmeasure = $priceworckmeasure;
		return $this;
	}

    /**
     * Get priceworckmeasure
     *
     * @return string 
     */
    public function getPriceworckmeasure()
    {
        return $this->priceworckmeasure;
    }

	/**
     * Set complexity
     *
     * @param string $complexity
     * @return plan
     */
    public function setComplexity($complexity)
    {
        $this->complexity = $complexity;
        return $this;
    }

    /**
     * Get complexity
     *
     * @return string 
     */
    public function getComplexity()
    {
        return $this->complexity;
    }

	/**
     * Set worcktime
     *
     * @param string $worcktime
     * @return plan
     */
    public function setWorcktime($worcktime)
    {
        $this->worcktime = $worcktime;
        return $this;
    }

    /**
     * Get worcktime
     *
     * @return string 
     */
    public function getWorcktime()
    {
        return $this->worcktime;
    }

	/**
     * Set sort
     *
     * @param integer $sort
     * @return plan
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

	/**
     * Get popplan
     *
     * @return \Top10\CabinetBundle\Entity\popplan
     */
    public function getPopplan()
    {
        return $this->popplan;
    }
}