<?php

namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class Categories
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=false)
     */
    private $libelle;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existe", type="boolean", nullable=false)
     */
    private $existe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Attributs", mappedBy="categories")
     */
    private $attribut;

    /**
     * @var \Categories
     *
     * @ORM\ManyToOne(targetEntity="Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_parente_id", referencedColumnName="id")
     * })
     */
    private $categorieParente;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribut = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return Categories
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set existe
     *
     * @param boolean $existe
     * @return Categories
     */
    public function setExiste($existe)
    {
        $this->existe = $existe;
    
        return $this;
    }

    /**
     * Get existe
     *
     * @return boolean 
     */
    public function getExiste()
    {
        return $this->existe;
    }

    /**
     * Add attribut
     *
     * @param \okazo\annoncesBundle\Entity\Attributs $attribut
     * @return Categories
     */
    public function addAttribut(\okazo\annoncesBundle\Entity\Attributs $attribut)
    {
        $this->attribut[] = $attribut;
    
        return $this;
    }

    /**
     * Remove attribut
     *
     * @param \okazo\annoncesBundle\Entity\Attributs $attribut
     */
    public function removeAttribut(\okazo\annoncesBundle\Entity\Attributs $attribut)
    {
        $this->attribut->removeElement($attribut);
    }

    /**
     * Get attribut
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttribut()
    {
        return $this->attribut;
    }

    /**
     * Set categorieParente
     *
     * @param \okazo\annoncesBundle\Entity\Categories $categorieParente
     * @return Categories
     */
    public function setCategorieParente(\okazo\annoncesBundle\Entity\Categories $categorieParente = null)
    {
        $this->categorieParente = $categorieParente;
    
        return $this;
    }

    /**
     * Get categorieParente
     *
     * @return \okazo\annoncesBundle\Entity\Categories 
     */
    public function getCategorieParente()
    {
        return $this->categorieParente;
    }
}