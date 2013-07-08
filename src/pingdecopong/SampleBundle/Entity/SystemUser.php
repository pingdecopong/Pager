<?php

namespace pingdecopong\SampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemUser
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SystemUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="namekana", type="string", length=255)
     */
    private $namekana;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;


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
     * @return SystemUser
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
     * Set namekana
     *
     * @param string $namekana
     * @return SystemUser
     */
    public function setNamekana($namekana)
    {
        $this->namekana = $namekana;
    
        return $this;
    }

    /**
     * Get namekana
     *
     * @return string 
     */
    public function getNamekana()
    {
        return $this->namekana;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return SystemUser
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
}
