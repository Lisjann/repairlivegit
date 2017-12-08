<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Tags
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class tags
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

	/**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=100)
     */
    private $code;


	/**
     * @ORM\OneToMany(targetEntity="Tagsforegin", mappedBy="tags", cascade={"persist", "remove"})
     */
    protected $tagsforegin;


    /*public function __construct()
    {
    	$this->tagsforegin = new ArrayCollection();
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

    /**
     * Set name
     *
     * @param string $name
     * @return tags
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
     * @param string $name
     * @return tags
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
     * Add Tagsforegin
     *
     * @param Top10\CabinetBundle\Entity\Tagsforegin $tagsforegin
     * @return tags
     */
    public function addTagsforegin(\Top10\CabinetBundle\Entity\Tagsforegin $tagsforegin)
    {
        $this->tagsforegin[] = $tagsforegin;
        return $this;
    }

    /**
     * Remove tagsforegin
     *
     * @param <variableType$tagsforegin
     */
    public function removeTagsforegin(\Top10\CabinetBundle\Entity\tagsforegin $tagsforegin)
    {
        $this->tagsforegin->removeElement($tagsforegin);
    }

    /**
     * Get tagsforegin
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTagsforegin()
    {
        return $this->tagsforegin;
    }
}