<?php

namespace okazo\logBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * log
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="okazo\logBundle\Repository\logRepository")
 */
class log
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
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;
    
    /**
     *
     * @ORM\Column(name="ip", type="string", length=16, nullable=true)
     */
    private $ip;
    
    /**
     * @ORM\Column(name="userAgent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @ORM\Column(name="uri", type="string", length=255, nullable=true)
     */
    private $uri;
    
    /**    
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="source", type="string", length=20, nullable=true)
     */
    private $source;
    
     /**         
     * @ORM\Column(name="categorie", type="string", length=255, nullable=true)
     */
    private $categorie;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen;


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
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return log
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    
        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return log
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return log
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    
        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean 
     */
    public function getSeen()
    {
        return $this->seen;
    }
}
