<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\plantypelist
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class plantypelist
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
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="plantypelist")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     */
    protected $plan;

	/**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

	/**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=100)
     */
    private $code;

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
     * @return plantypelist
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
     * Set value
     *
     * @param string $value
     * @return plantypelist
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getValue()
    {
        return $this->value;
    }

	/**
     * Set code
     *
     * @param string $code
     * @return plantypelist
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
}