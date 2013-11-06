<?php

namespace okazo\geoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * country
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="okazo\geoBundle\Repository\countryRepository")
 */
class country
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
     * @ORM\Column(name="code", type="string", length=3)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="fr", type="string", length=200)
     */
    private $fr;

    /**
     * @var string
     *
     * @ORM\Column(name="en", type="string", length=200)
     */
    private $en;

    /**
     *@ORM\OneToMany(targetEntity="city", mappedBy="id")     
     */
    private $cities;

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
     * Set code
     *
     * @param string $code
     * @return country
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
     * Set fr
     *
     * @param string $fr
     * @return country
     */
    public function setFr($fr)
    {
        $this->fr = $fr;
    
        return $this;
    }

    /**
     * Get fr
     *
     * @return string 
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set en
     *
     * @param string $en
     * @return country
     */
    public function setEn($en)
    {
        $this->en = $en;
    
        return $this;
    }

    /**
     * Get en
     *
     * @return string 
     */
    public function getEn()
    {
        return $this->en;
    }
}