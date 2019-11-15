<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Top10\CabinetBundle\Entity\Anket
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class anket
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
	 * @ORM\ManyToOne(targetEntity="Rooms", inversedBy="anket")
	 * @ORM\JoinColumn(name="rooms_id", referencedColumnName="id")
	*/
	protected $rooms;

	/**
	 * @var string $familymembers
	 *
	 * @ORM\Column(name="familymembers", type="string", length=500)
	*/ 
	private $familymembers;

	/**
	 * @var string $familymembersplan
	 *
	 * @ORM\Column(name="familymembersplan", type="string", length=500)
	*/ 
	private $familymembersplan;

	/**
	 * @var string $hobby
	 *
	 * @ORM\Column(name="hobby", type="string", length=500)
	*/ 
	private $hobby;

	/**
	 * @var string $animals
	 *
	 * @ORM\Column(name="animals", type="string", length=500)
	*/ 
	private $animals;

	/**
	 * @var string $guests
	 *
	 * @ORM\Column(name="guests", type="string", length=500)
	*/ 
	private $guests;

	/**
	 * @var string $zone
	 *
	 * @ORM\Column(name="zone", type="string", length=500)
	*/ 
	private $zone;

	/**
	 * @var string $redevelopment
	 *
	 * @ORM\Column(name="redevelopment", type="string", length=500)
	*/ 
	private $redevelopment;

	/**
	 * @var string $style
	 *
	 * @ORM\Column(name="style", type="string", length=500)
	*/ 
	private $style;

	/**
	 * @var string $material
	 *
	 * @ORM\Column(name="material", type="string", length=500)
	*/ 
	private $material;

	/**
	 * @var string $technique
	 *
	 * @ORM\Column(name="technique", type="string", length=500)
	*/ 
	private $technique;

	/**
	 * @var string $electrician
	 *
	 * @ORM\Column(name="electrician", type="string", length=500)
	*/ 
	private $electrician;

	/**
	 * @var string $furniture
	 *
	 * @ORM\Column(name="furniture", type="string", length=500)
	*/ 
	private $furniture;

	/**
	 * @var string $habitatquality
	 *
	 * @ORM\Column(name="habitatquality", type="string", length=500)
	*/ 
	private $habitatquality;

	/**
	 * @var string $plants
	 *
	 * @ORM\Column(name="plants", type="string", length=500)
	*/ 
	private $plants;

	/**
	 * @var string $save
	 *
	 * @ORM\Column(name="save", type="string", length=500)
	*/ 
	private $save;

	/**
	 * @var string $other
	 *
	 * @ORM\Column(name="other", type="string", length=500)
	*/ 
	private $other;


	public function __construct()
	{
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
     * Set rooms
     *
     * @param \Top10\CabinetBundle\Entity\rooms $rooms
     * @return anket
     */
    public function setRooms(\Top10\CabinetBundle\Entity\rooms $rooms = null)
    {
        $this->rooms = $rooms;
        return $this;
    }

    /**
     * Get rooms
     *
     * @return \Top10\CabinetBundle\Entity\rooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }


	/**
	 * Set familymembers
	 *
	 * @param string $familymembers
	 * @return anket
	 */
	public function setFamilymembers($familymembers)
	{
		$this->familymembers = $familymembers;
		return $this;
	}

	/**
	 * Get familymembers
	 *
	 * @return string 
	 */
	public function getFamilymembers()
	{
		return $this->familymembers;
	}

	/**
	 * Set familymembersplan
	 *
	 * @param string $familymembersplan
	 * @return anket
	 */
	public function setFamilymembersplan($familymembersplan)
	{
		$this->familymembersplan = $familymembersplan;
		return $this;
	}

	/**
	 * Get familymembersplan
	 *
	 * @return string 
	 */
	public function getFamilymembersplan()
	{
		return $this->familymembersplan;
	}

	/**
	 * Set hobby
	 *
	 * @param string $hobby
	 * @return anket
	 */
	public function setHobby($hobby)
	{
		$this->hobby = $hobby;
		return $this;
	}

	/**
	 * Get hobby
	 *
	 * @return string 
	 */
	public function getHobby()
	{
		return $this->hobby;
	}

	/**
	 * Set animals
	 *
	 * @param string $animals
	 * @return anket
	 */
	public function setAnimals($animals)
	{
		$this->animals = $animals;
		return $this;
	}

	/**
	 * Get animals
	 *
	 * @return string 
	 */
	public function getAnimals()
	{
		return $this->animals;
	}

	/**
	 * Set guests
	 *
	 * @param string $guests
	 * @return anket
	 */
	public function setGuests($guests)
	{
		$this->guests = $guests;
		return $this;
	}

	/**
	 * Get guests
	 *
	 * @return string 
	 */
	public function getGuests()
	{
		return $this->guests;
	}
	/**
	 * Set zone
	 *
	 * @param string $zone
	 * @return anket
	 */
	public function setZone($zone)
	{
		$this->zone = $zone;
		return $this;
	}

	/**
	 * Get zone
	 *
	 * @return string 
	 */
	public function getZone()
	{
		return $this->zone;
	}


	/**
	 * Set redevelopment
	 *
	 * @param string $redevelopment
	 * @return anket
	 */
	public function setRedevelopment($redevelopment)
	{
		$this->redevelopment = $redevelopment;
		return $this;
	}

	/**
	 * Get redevelopment
	 *
	 * @return string 
	 */
	public function getRedevelopment()
	{
		return $this->redevelopment;
	}

	/**
	 * Set style
	 *
	 * @param string $style
	 * @return anket
	 */
	public function setStyle($style)
	{
		$this->style = $style;
		return $this;
	}

	/**
	 * Get style
	 *
	 * @return string 
	 */
	public function getStyle()
	{
		return $this->style;
	}

	/**
	 * Set material
	 *
	 * @param string $material
	 * @return anket
	 */
	public function setMaterial($material)
	{
		$this->material = $material;
		return $this;
	}

	/**
	 * Get material
	 *
	 * @return string 
	 */
	public function getMaterial()
	{
		return $this->material;
	}

	/**
	 * Set technique
	 *
	 * @param string $technique
	 * @return anket
	 */
	public function setTechnique($technique)
	{
		$this->technique = $technique;
		return $this;
	}

	/**
	 * Get technique
	 *
	 * @return string 
	 */
	public function getTechnique()
	{
		return $this->technique;
	}

	/**
	 * Set electrician
	 *
	 * @param string $electrician
	 * @return anket
	 */
	public function setElectrician($electrician)
	{
		$this->electrician = $electrician;
		return $this;
	}

	/**
	 * Get electrician
	 *
	 * @return string 
	 */
	public function getElectrician()
	{
		return $this->electrician;
	}

	/**
	 * Set furniture
	 *
	 * @param string $furniture
	 * @return anket
	 */
	public function setFurniture($furniture)
	{
		$this->furniture = $furniture;
		return $this;
	}

	/**
	 * Get furniture
	 *
	 * @return string 
	 */
	public function getFurniture()
	{
		return $this->furniture;
	}


	/**
	 * Set habitatquality
	 *
	 * @param string $habitatquality
	 * @return anket
	 */
	public function setHabitatquality($habitatquality)
	{
		$this->habitatquality = $habitatquality;
		return $this;
	}

	/**
	 * Get habitatquality
	 *
	 * @return string 
	 */
	public function getHabitatquality()
	{
		return $this->habitatquality;
	}

	/**
	 * Set plants
	 *
	 * @param string $plants
	 * @return anket
	 */
	public function setPlants($plants)
	{
		$this->plants = $plants;
		return $this;
	}

	/**
	 * Get plants
	 *
	 * @return string 
	 */
	public function getPlants()
	{
		return $this->plants;
	}

	/**
	 * Set save
	 *
	 * @param string $save
	 * @return anket
	 */
	public function setSave($save)
	{
		$this->save = $save;
		return $this;
	}

	/**
	 * Get save
	 *
	 * @return string 
	 */
	public function getSave()
	{
		return $this->save;
	}

	/**
	 * Set other
	 *
	 * @param string $other
	 * @return anket
	 */
	public function setOther($other)
	{
		$this->other = $other;
		return $this;
	}

	/**
	 * Get other
	 *
	 * @return string 
	 */
	public function getOther()
	{
		return $this->other;
	}

}