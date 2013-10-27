<?php

namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attributs
 *
 * @ORM\Table(name="attributs")
 * @ORM\Entity
 */
class Attributs
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Annonces", inversedBy="attribut")
     * @ORM\JoinTable(name="attributsannonce",
     *   joinColumns={
     *     @ORM\JoinColumn(name="attribut", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="annonce", referencedColumnName="id")
     *   }
     * )
     */
    private $annonce;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Categories", inversedBy="attribut")
     * @ORM\JoinTable(name="attributspossibles",
     *   joinColumns={
     *     @ORM\JoinColumn(name="attribut", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="categories_id", referencedColumnName="id")
     *   }
     * )
     */
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->annonce = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Attributs
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
     * Set type
     *
     * @param string $type
     * @return Attributs
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add annonce
     *
     * @param \okazo\annoncesBundle\Entity\Annonces $annonce
     * @return Attributs
     */
    public function addAnnonce(\okazo\annoncesBundle\Entity\Annonces $annonce)
    {
        $this->annonce[] = $annonce;
    
        return $this;
    }

    /**
     * Remove annonce
     *
     * @param \okazo\annoncesBundle\Entity\Annonces $annonce
     */
    public function removeAnnonce(\okazo\annoncesBundle\Entity\Annonces $annonce)
    {
        $this->annonce->removeElement($annonce);
    }

    /**
     * Get annonce
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * Add categories
     *
     * @param \okazo\annoncesBundle\Entity\Categories $categories
     * @return Attributs
     */
    public function addCategorie(\okazo\annoncesBundle\Entity\Categories $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \okazo\annoncesBundle\Entity\Categories $categories
     */
    public function removeCategorie(\okazo\annoncesBundle\Entity\Categories $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}