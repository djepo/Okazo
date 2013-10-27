<?php

namespace okazo\geoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * city
 *
 * @ORM\Table(indexes={@ORM\Index(name="citylatitude_idx", columns={"citylatitude"}),
 *                     @ORM\Index(name="citylongitude_idx", columns={"citylongitude"})
 *                    }
 *           )
 * @ORM\Entity(repositoryClass="okazo\geoBundle\Entity\cityRepository")
 */
class city
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
     * @ORM\Column(name="cityname", type="string", length=50)
     */
    private $cityname;

    /**
     * @var string
     *
     * @ORM\Column(name="citycp", type="string", length=5)
     */
    private $citycp;

    /**
     * @var float
     *
     * @ORM\Column(name="citylatitude", type="decimal", precision=9, scale=6)     
     */
    private $citylatitude;
    
    /**
     * @var float
     *
     * @ORM\Column(name="citylongitude", type="decimal", precision=9, scale=6)
     */
    private $citylongitude;

    /**
     * @var string
     *
     * @ORM\Column(name="cityurl", type="string", length=50)
     */
    private $cityurl;
    
    
    /**
    *@ORM\OneToMany(targetEntity="okazo\annoncesBundle\Entity\Annonces", mappedBy="city", cascade={"remove", "persist"})
    */    
    private $annonces;


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
     * Set nom
     *
     * @param string $nom
     * @return city
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return city
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    
        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return city
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return city
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return city
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
}