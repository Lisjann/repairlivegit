<?php

namespace Top10\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Top10\CabinetBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class user extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

	/**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * @var string $new
     *
     * @ORM\Column(name="new", type="boolean", nullable=true)
     */
    private $new;
    
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

	/**
     * @var string $location
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $location = null;

	/**
     * @var string $avatar
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

	/**
     * @ORM\OneToMany(targetEntity="Homes", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $homes;


    public function __construct()
    {
    	parent::__construct();
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
     * Set message
     *
     * @param text $message
     * @return User
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return text
     */
    public function getMessage()
    {
        return $this->message;
    }

	/**
     * Set content
     *
     * @param text $content
     * @return User
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
     * Set new
     *
     * @param boolean $new
     * @return User
     */
    public function setNew($new)
    {
        $this->new = $new;
        return $this;
    }

    /**
     * Get new
     *
     * @return boolean 
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Set created
     *
     * @param \Datetime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \Datetime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return \Datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

	/**
     * Город
     *
     * @param $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }
    /**
     * Фактический адресс
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

	/**
     * Картинка аватара
     *
     * @param string $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

	/**
     * Картинка аватара
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

	/**
     * Add homes
     *
     * @param Top10\CabinetBundle\Entity\homes $homes
     * @return homes
     */
    public function addHomes(\Top10\CabinetBundle\Entity\homes $homes)
    {
        $this->homes[] = $homes;
        return $this;
    }

    /**
     * Remove homes
     *
     * @param <variableType$homes
     */
    public function removeHomes(\Top10\CabinetBundle\Entity\homes $homes)
    {
        $this->homes->removeElement($homes);
    }

	/**
	 * Get homes
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getHomes()
	{
		return $this->homes;
	}
}