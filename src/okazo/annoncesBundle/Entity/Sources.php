<?php
namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**  
 * @ORM\Table(name="sources")
 * @ORM\Entity
 */
class Sources 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * Nom du site WEB de petites annonces
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * URL de base du site WEB de petites annonces
     *
     * @ORM\Column(name="baseurl", type="string", length=255, nullable=false)
    */
    private $baseurl;
    
    /**
     *@ORM\Column(name="botBaseURL", type="string", length=255)     
     */
    private $botBaseURL;
    
    /**
     *@ORM\Column(name="botSuffixe", type="string", length=255)     
     */
    private $botSuffixe;
    
    /**
     *@ORM\Column(name="botPageParameter", type="string", length=50)
     */
    private $botPageParameter;
    
    /**
     * Délai en minutes avant prochain scan par le robot
     * 
     * @ORM\Column(name="refreshDelay", type="integer")     
     */
    private $refreshDelay;
    
    /**
     * Nombre de pages max à rapatrier par scan
     * @ORM\Column(name="maxPages", type="integer")
     */
    private $maxPages;
    
    /**          
     * @ORM\Column(name="mode", type="string", length=10, nullable=true)
     */
    private $mode;
    
    /**
     * @ORM\Column(name="existe", type="boolean")     
     */
    private $existe;
    
    /**
     *@ORM\OneToMany(targetEntity="Annonces", mappedBy="websiteId")     
    */
    private $annonces;
}
