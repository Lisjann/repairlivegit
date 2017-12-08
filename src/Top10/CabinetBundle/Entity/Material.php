<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Material
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class material
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
     * @ORM\OneToMany(targetEntity="Material", mappedBy="parent")
 	 * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $children;

	/**
     * @ORM\ManyToOne(targetEntity="Material", inversedBy="children")
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
     * @var string $complexity
     * Сложность работ оценка от 1 до 5
     * @ORM\Column(name="complexity", type="integer", length=1)
    */
	private $complexity;

	/**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", length=11)
	 * @ORM\OrderBy({"sort" = "ASC"})
     */
	private $sort;

	/**
     * @ORM\OneToMany(targetEntity="Materialforegin", mappedBy="material", cascade={"persist", "remove"})
     */
    protected $materialforegin;


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
	 * @param Top10\CabinetBundle\Entity\Material $parent
	 * @return Material
	 */
	public function setParent( Material $parent = null )
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return Top10\CabinetBundle\Entity\Material 
	 */
	public function getParent()
	{
		return $this->parent;
	}

    /**
     * Set name
     *
     * @param string $name
     * @return material
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
     * @return material
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
     * @return material
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
     * Add Materialforegin
     *
     * @param Top10\CabinetBundle\Entity\Materialforegin $materialforegin
     * @return material
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

	/**
     * Set image
     *
     * @param string $image
     * @return material
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
     * @return material
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
     * @return material
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
	 * @return material
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
	 * @return material
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
     * @return material
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
     * @return material
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
	 * @return material
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
	 * @return material
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
     * Set complexity
     *
     * @param string $complexity
     * @return material
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
     * Set sort
     *
     * @param integer $sort
     * @return material
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