<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\propertiestypelist
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class propertiestypelist
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
     * @ORM\ManyToOne(targetEntity="Properties", inversedBy="propertiestypelist")
     * @ORM\JoinColumn(name="properties_id", referencedColumnName="id")
     */
    protected $properties;

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
     * Set properties
     *
     * @param Top10\CabinetBundle\Entity\Properties $properties
     * @return propertiestypelist
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
     * Set value
     *
     * @param string $value
     * @return propertiestypelist
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
     * @return propertiestypelist
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