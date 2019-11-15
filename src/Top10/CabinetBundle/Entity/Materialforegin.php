<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\materialforegin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class materialforegin
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
     * @ORM\ManyToOne(targetEntity="Material", inversedBy="materialforegin")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     */
    protected $material;

		/**
     * @ORM\ManyToOne(targetEntity="Planforegin", inversedBy="materialforegin")
     * @ORM\JoinColumn(name="planforegin_id", referencedColumnName="id")
    */
    protected $planforegin;

	/**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=100, nullable=true)
     */
    private $value;

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
     * @var text $measure
     *
     * @ORM\Column(name="measure", type="string", length=20)
     */
    private $measure;

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
     * Set material
     *
     * @param Top10\CabinetBundle\Entity\Material $material
     * @return materialforegin
     */
    public function setMaterial(\Top10\CabinetBundle\Entity\Material $material = null)
    {
        $this->material = $material;
        return $this;
    }

    /**
     * Get material
     *
     * @return Top10\CabinetBundle\Entity\Material 
     */
    public function getMaterial()
    {
        return $this->material;
    }

	/**
     * Set planforegin
     *
     * @param \Top10\CabinetBundle\Entity\Planforegin $planforegin
     * @return material
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
     * Set value
     *
     * @param string $value
     * @return materialforegin
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
     * Set name
     *
     * @param string $name
     * @return materialforegin
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
     * @return materialforegin
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
     * @return materialforegin
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
     * @return materialforegin
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
	 * Set measure
	 *
	 * @param string $measure
	 * @return material
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
}