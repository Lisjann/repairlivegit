<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Top10\CabinetBundle\Entity\Popplan
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class popplan
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
	 * @var string $daystobegin
	 * дней до начала
	 * @ORM\Column(name="daystobegin", type="integer", length=3)
	*/
	private $daystobegin;

	/**
	 * @var string $daystoend
	 * дней до конца
	 * @ORM\Column(name="daystoend", type="integer", length=3)
	*/
	private $daystoend;

	/**
	 * @var string $dayworck
	 * дней  работ
	 * @ORM\Column(name="dayworck", type="integer", length=3)
	*/
	private $dayworck;

	/**
	 * @var string $countplanyes
	 * дочерних активных пунктов
	 * @ORM\Column(name="countplanyes", type="integer", length=3)
	*/
	private $countplanyes;

	/**
	 * @var string $countfinished
	 * дочерних активных пунктов
	 * @ORM\Column(name="countfinished", type="integer", length=3)
	*/
	private $countfinished;

	/**
	 * @var string $sumprice
	 * дочерних активных пунктов
	 * @ORM\Column(name="sumprice", type="integer", length=9)
	*/
	private $sumprice;

		/**
	 * @var string $sumpricefinished
	 * дочерних активных пунктов
	 * @ORM\Column(name="sumpricefinished", type="integer", length=9)
	*/
	private $sumpricefinished;


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
	 * Get daystobegin
	 *
	 * @return integer 
	 */
	public function getDaystobegin()
	{
		return $this->daystobegin;
	}

	/**
	 * Get daystoend
	 *
	 * @return integer 
	 */
	public function getDaystoend()
	{
		return $this->daystoend;
	}

	/**
	 * Get dayworck
	 *
	 * @return integer 
	 */
	public function getDayworck()
	{
		return $this->dayworck;
	}

	/**
	 * Get countplanyes
	 *
	 * @return integer 
	 */
	public function getCountplanyes()
	{
		return $this->countplanyes;
	}

	/**
	 * Get countfinished
	 *
	 * @return integer 
	 */
	public function getCountfinished()
	{
		return $this->countfinished;
	}

	/**
	 * Get sumprice
	 *
	 * @return integer 
	 */
	public function getSumprice()
	{
		return $this->sumprice;
	}

		/**
	 * Get sumpricefinished
	 *
	 * @return integer 
	 */
	public function getSumpricefinished()
	{
		return $this->sumpricefinished;
	}
}