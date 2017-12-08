<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\planproperties
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class planproperties
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
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="planproperties")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     */
    protected $plan;

	/**
     * @ORM\ManyToOne(targetEntity="Properties", inversedBy="planproperties")
     * @ORM\JoinColumn(name="properties_id", referencedColumnName="id")
     */
    protected $properties;

	/**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", length=11)
	 * @ORM\OrderBy({"sort" = "DESC"})
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
     * Set plan
     *
     * @param Top10\CabinetBundle\Entity\Plan $plan
     * @return planproperties
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
     * Set properties
     *
     * @param Top10\CabinetBundle\Entity\Properties $properties
     * @return planproperties
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
     * Set sort
     *
     * @param integer $sort
     * @return planproperties
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