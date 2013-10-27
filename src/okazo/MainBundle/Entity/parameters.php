<?php

namespace okazo\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * parameters
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="okazo\MainBundle\Repository\parametersRepository")
 */
class parameters
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
     * @var boolean
     *
     * @ORM\Column(name="showLogin", type="boolean")
     */
    private $showLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showLanguageChooser", type="boolean")
     */
    private $showLanguageChooser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showHeader", type="boolean")
     */
    private $showHeader;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useFacebookLogin", type="boolean")
     */
    private $useFacebookLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useInternalLogin", type="boolean")
     */
    private $useInternalLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useGoogleAnalytics", type="boolean")
     */
    private $useGoogleAnalytics;


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
     * Set showLogin
     *
     * @param boolean $showLogin
     * @return parameters
     */
    public function setShowLogin($showLogin)
    {
        $this->showLogin = $showLogin;
    
        return $this;
    }

    /**
     * Get showLogin
     *
     * @return boolean 
     */
    public function getShowLogin()
    {
        return $this->showLogin;
    }

    /**
     * Set showLanguageChooser
     *
     * @param boolean $showLanguageChooser
     * @return parameters
     */
    public function setShowLanguageChooser($showLanguageChooser)
    {
        $this->showLanguageChooser = $showLanguageChooser;
    
        return $this;
    }

    /**
     * Get showLanguageChooser
     *
     * @return boolean 
     */
    public function getShowLanguageChooser()
    {
        return $this->showLanguageChooser;
    }

    /**
     * Set showHeader
     *
     * @param boolean $showHeader
     * @return parameters
     */
    public function setShowHeader($showHeader)
    {
        $this->showHeader = $showHeader;
    
        return $this;
    }

    /**
     * Get showHeader
     *
     * @return boolean 
     */
    public function getShowHeader()
    {
        return $this->showHeader;
    }

    /**
     * Set useFacebookLogin
     *
     * @param boolean $useFacebookLogin
     * @return parameters
     */
    public function setUseFacebookLogin($useFacebookLogin)
    {
        $this->useFacebookLogin = $useFacebookLogin;
    
        return $this;
    }

    /**
     * Get useFacebookLogin
     *
     * @return boolean 
     */
    public function getUseFacebookLogin()
    {
        return $this->useFacebookLogin;
    }

    /**
     * Set useInternalLogin
     *
     * @param boolean $useInternalLogin
     * @return parameters
     */
    public function setUseInternalLogin($useInternalLogin)
    {
        $this->useInternalLogin = $useInternalLogin;
    
        return $this;
    }

    /**
     * Get useInternalLogin
     *
     * @return boolean 
     */
    public function getUseInternalLogin()
    {
        return $this->useInternalLogin;
    }

    /**
     * Set useGoogleAnalytics
     *
     * @param boolean $useGoogleAnalytics
     * @return parameters
     */
    public function setUseGoogleAnalytics($useGoogleAnalytics)
    {
        $this->useGoogleAnalytics = $useGoogleAnalytics;
    
        return $this;
    }

    /**
     * Get useGoogleAnalytics
     *
     * @return boolean 
     */
    public function getUseGoogleAnalytics()
    {
        return $this->useGoogleAnalytics;
    }
}
