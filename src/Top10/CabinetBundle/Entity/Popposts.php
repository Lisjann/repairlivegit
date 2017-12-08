<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\popposts
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class popposts
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
     * @var string $popposts
     *
     * @ORM\Column(name="popposts", type="integer")
     */
    private $popposts;
	 
	 /**
     * @var string $cntlikes
     *
     * @ORM\Column(name="cntlikes", type="integer")
     */
    private $cntlikes;

	/**
     * @var string $cntcomments
     *
     * @ORM\Column(name="cntcomments", type="integer")
     */
    private $cntcomments;


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
     * Get popposts
     *
     * @return string 
     */
    public function getPopposts()
    {
        return $this->popposts;
    }

	/**
     * Get cntlikes
     *
     * @return string 
     */
    public function getCntlikes()
    {
        return $this->cntlikes;
    }


    /**
     * Get cntcomments
     *
     * @return string 
     */
    public function getCntcomments()
    {
        return $this->cntcomments;
    }
}