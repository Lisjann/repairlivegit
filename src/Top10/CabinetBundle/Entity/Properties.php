<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\properties
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class properties
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
     * @ORM\OneToMany(targetEntity="properties", mappedBy="parent")
	 * @ORM\OrderBy({"created" = "DESC"})
     */
    private $children;

	/**
     * @ORM\ManyToOne(targetEntity="properties", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")	 
     */
    protected $parent;

	/**
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="properties")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     */
    protected $plan;

	/**
     * @var string $isin
     *
     * @ORM\Column(name="isin", type="string", length=25)
     */
    private $isin;

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
     * @var string $measure
     *
     * @ORM\Column(name="measure", type="string", nullable=true)
     */
    private $measure;

	/**
     * @ORM\OneToMany(targetEntity="Propertiesforegin", mappedBy="properties", cascade={"persist", "remove"})
     */
    protected $propertiesforegin;

	/**
     * @ORM\OneToMany(targetEntity="Propertiestypelist", mappedBy="properties", cascade={"persist", "remove"})
     */
    protected $propertiestypelist;

	/**
	 * @ORM\OneToMany(targetEntity="Planproperties", mappedBy="properties", cascade={"persist", "remove"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	*/
	protected $planproperties;


    /*public function __construct()
    {
    	$this->propertiesforegin = new ArrayCollection();
    	// your own logic
    }*/

    
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
	 * @param Top10\CabinetBundle\Entity\Properties $parent
	 * @return Properties
	 */
	public function setParent( Properties $parent = null )
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return Top10\CabinetBundle\Entity\Properties 
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
     * Set plan
     *
     * @param Top10\CabinetBundle\Entity\Plan $plan
     * @return Properties
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
     * Set isin
     *
     * @param string $isin
     * @return properties
     */
    public function setIsin($isin)
    {
        $this->isin = $isin;
        return $this;
    }

    /**
     * Get isin
     *
     * @return string 
     */
    public function getIsin()
    {
        return $this->isin;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return properties
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
     * @return properties
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
     * @return properties
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
     * Add Propertiesforegin
     *
     * @param Top10\CabinetBundle\Entity\Propertiesforegin $propertiesforegin
     * @return properties
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
     * Add Propertiestypelist
     *
     * @param Top10\CabinetBundle\Entity\Propertiestypelist $propertiestypelist
     * @return properties
     */
    public function addPropertiestypelist(\Top10\CabinetBundle\Entity\Propertiestypelist $propertiestypelist)
    {
        $this->propertiestypelist[] = $propertiestypelist;
        return $this;
    }

    /**
     * Remove propertiestypelist
     *
     * @param <variableType$propertiestypelist
     */
    public function removePropertiestypelist(\Top10\CabinetBundle\Entity\propertiestypelist $propertiestypelist)
    {
        $this->propertiestypelist->removeElement($propertiestypelist);
    }

    /**
     * Get propertiestypelist
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPropertiestypelist()
    {
        return $this->propertiestypelist;
    }

	/**
     * Set measure
     *
     * @param string $measure
     * @return properties
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;
        return $this;
    }

    /**
     * Get measure
     *
     * @return string 
     */
    public function getMeasure()
    {
        return $this->measure;
    }

	/**
     * Add Planproperties
     *
     * @param Top10\CabinetBundle\Entity\Planproperties $planproperties
     * @return properties
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
}