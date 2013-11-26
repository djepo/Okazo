<?php

namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Annonces
 *
 * @ORM\Table(name="annonces")
 * @ORM\Entity(repositoryClass="okazo\annoncesBundle\Repository\annoncesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Annonces {

    /**
     * @ORM\Column(name="id", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(name="foreign_id", type="text", nullable=true)
     */
    private $foreignId;

    /**
     * @ORM\Column(name="foreign_timestamp", type="datetime", nullable=true)
     */
    private $foreignTimestamp;

    /**
     * @ORM\ManyToOne(targetEntity="Sources", inversedBy="annonces")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="websiteId", referencedColumnName="id", nullable=true)
     * })
     */
    private $websiteId;

    /**
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @ORM\Column(name="texte", type="text", nullable=false)
     */
    private $texte;

    /**
     * @ORM\Column(name="lien", type="string", length=255, nullable=true)
     */
    private $lien;

    /**
     * @ORM\Column(name="website_categorie", type="string", length=50, nullable=true)
     */
    private $websiteCategorie;

    /**
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     *@ORM\Column(name="datecreated", type="datetime", nullable=true)
     */
    private $datecreated;

    /**
     * @ORM\Column(name="horodatageparse", type="datetime", nullable=true)
     */
    private $horodatageparse;

    /**
     * @ORM\Column(name="lastexistencecheck", type="datetime", nullable=true)
     */
    private $lastexistencecheck;

    /**
     * @ORM\Column(name="existe", type="boolean", nullable=false)
     */
    private $existe;

    /**
     * @ORM\ManyToMany(targetEntity="Attributs", mappedBy="annonce")
     */
    private $attribut;

    /**
     * @ORM\ManyToOne(targetEntity="Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="okazo\geoBundle\Entity\city", inversedBy="annonces", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cityid", referencedColumnName="id")
     * })
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="okazo\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     * })
     */
    private $fosUser;

    /**
     * @ORM\OneToMany(targetEntity="Images", mappedBy="annonce", cascade={"remove", "persist"})
     * @Assert\Valid()
     */
    private $images;
    
    /**
     * @ORM\Column(name="latitude", type="decimal", precision=9, scale=6)      
     */
    private $latitude;
    
    /**
     * @ORM\Column(name="longitude", type="decimal", precision=9, scale=6)      
     */
    private $longitude;

    /**
     * Get id
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }
    public function setId($id = null) {
        if ($id != null) {
            $this->id = $id;
        } else {
            $objDateTime = new \DateTime('NOW');
            $this->id = $objDateTime->format('YmdHis').'-'.uniqid();
        }
    }

    /**
     * Set foreignId
     *
     * @param string $foreignId
     * @return Annonces
     */
    public function setForeignId($foreignId) {
        $this->foreignId = $foreignId;

        return $this;
    }
    /**
     * Get foreignId
     *
     * @return string
     */
    public function getForeignId() {
        return $this->foreignId;
    }

    /**
     * Set foreignTimestamp
     *
     * @param \DateTime $foreignTimestamp
     * @return Annonces
     */
    public function setForeignTimestamp($foreignTimestamp) {
        $this->foreignTimestamp = $foreignTimestamp;

        return $this;
    }
    /**
     * Get foreignTimestamp
     *
     * @return \DateTime
     */
    public function getForeignTimestamp() {
        return $this->foreignTimestamp;
    }

    /**
     * Set websiteId
     *
     * @param string $websiteId
     * @return Annonces
     */
    public function setWebsiteId($websiteId) {
        $this->websiteId = $websiteId;

        return $this;    }
    /**
     * Get websiteId
     *
     * @return string
     */
    public function getWebsiteId() {
        return $this->websiteId;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Annonces
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }
    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Annonces
     */
    public function setTexte($texte) {
        $this->texte = $texte;

        return $this;
    }
    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte() {
        return $this->texte;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Annonces
     */
    public function setLien($lien) {
        $this->lien = $lien;

        return $this;
    }
    /**
     * Get lien
     *
     * @return string
     */
    public function getLien() {
        return $this->lien;
    }

    /**
     * Set websiteCategorie
     *
     * @param string $websiteCategorie
     * @return Annonces
     */
    public function setWebsiteCategorie($websiteCategorie) {
        $this->websiteCategorie = $websiteCategorie;

        return $this;
    }
    /**
     * Get websiteCategorie
     *
     * @return string
     */
    public function getWebsiteCategorie() {
        return $this->websiteCategorie;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Annonces
     */
    public function setPrix($prix) {
        $this->prix = $prix;

        return $this;
    }
    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * Set horodatageparse
     *
     * @param \DateTime $horodatageparse
     * @return Annonces
     */
    public function setHorodatageparse($horodatageparse) {
        $this->horodatageparse = $horodatageparse;

        return $this;
    }
    /**
     * Get horodatageparse
     *
     * @return \DateTime
     */
    public function getHorodatageparse() {
        return $this->horodatageparse;
    }

    /**
     * Add attribut
     *
     * @param \okazo\annoncesBundle\Entity\Attributs $attribut
     * @return Annonces
     */
    public function addAttribut(\okazo\annoncesBundle\Entity\Attributs $attribut) {
        $this->attribut[] = $attribut;

        return $this;
    }
    /**
     * Remove attribut
     *
     * @param \okazo\annoncesBundle\Entity\Attributs $attribut
     */
    public function removeAttribut(\okazo\annoncesBundle\Entity\Attributs $attribut) {
        $this->attribut->removeElement($attribut);
    }
    /**
     * Get attribut
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttribut() {
        return $this->attribut;
    }

    /**
     * Set categorie
     *
     * @param \okazo\annoncesBundle\Entity\Categories $categorie
     * @return Annonces
     */
    public function setCategorie(\okazo\annoncesBundle\Entity\Categories $categorie = null) {
        $this->categorie = $categorie;

        return $this;
    }
    /**
     * Get categorie
     *
     * @return \okazo\annoncesBundle\Entity\Categories
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Set fosUser
     *
     * @param \okazo\UserBundle\Entity\FosUser $fosUser
     * @return Annonces
     */
    public function setFosUser(\okazo\UserBundle\Entity\User $fosUser = null) {
        $this->fosUser = $fosUser;

        return $this;
    }
    /**
     * Get fosUser
     *
     * @return \okazo\UserBundle\Entity\FosUser
     */
    public function getFosUser() {
        return $this->fosUser;
    }

    /**
     * Set City
     *
     * @param \okazo\geoBundle\Entity\city $city
     * @return Annonces
     */
    public function setCity(\okazo\geoBundle\Entity\city $city = null) {
        $this->city = $city;

        return $this;
    }
    /**
     * Get city
     *
     * @return \okazo\geoBundle\Entity\city
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Add images
     *
     * @param \okazo\annoncesBundle\Entity\images $images
     * @return Annonces
     */
    public function addImage(\okazo\annoncesBundle\Entity\images $image) {
        if(!empty($image->file)) {                  //On teste que le formulaire ne nous renvoie pas de champ file vide.
            $image->setAnnonce($this);
            $this->images[] = $image;
        }
        return $this;
    }
    /**
     * Remove images
     *
     * @param \okazo\annoncesBundle\Entity\images $images
     */
    public function removeImage(\okazo\annoncesBundle\Entity\images $image) {
        $this->images->removeElement($image);
    }
    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Set lastexistencecheck
     *
     * @param \DateTime $lastexistencecheck
     * @return Annonces
     */
    public function setLastexistencecheck($lastexistencecheck) {
        $this->lastexistencecheck = $lastexistencecheck;

        return $this;
    }
    /**
     * Get lastexistencecheck
     *
     * @return \DateTime
     */
    public function getLastexistencecheck() {
        return $this->lastexistencecheck;
    }

    /**
     * Set existe
     *
     * @param boolean $existe
     * @return Annonces
     */
    public function setExiste($existe) {
        $this->existe = $existe;

        return $this;
    }
    /**
     * Get existe
     *
     * @return boolean
     */
    public function getExiste() {
        return $this->existe;
    }

    /** @ORM\PreRemove */
    public function preRemove() {
        //global $kernel;
        //if ('AppCache' == get_class($kernel)) {
        //    $kernel = $kernel->getKernel();
        //}
        //$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        //$okazoAnnoncesServices = $kernel->getContainer()->get('okazo.annonces');
        //before entity deletion, we will delete the pictures, if they exists
        //recherche des éventuelles images liées à l'annonce
        //$pictures = $em->getConnection()->executeQuery("SELECT images.* FROM images WHERE images.annonceId='" . $this->getId() . "'")->fetchAll();

        //if ($pictures) {
            //$okazoAnnoncesServices->deletePictures($pictures);
        //}
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        //$this->id = uniqid();        dégagé dans addImage, car on en a besoin avant persist
        //var_dump("Annonce PrePersist <br>");
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist() {
        //var_dump('Annonce PostPersist <br>');
        //Envoi de l'identifiant de l'annonce dans le champ lié de l'entité image
        foreach ($this->images as $image) {
            $image->setAnnonce($this);
        }
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->attribut = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Annonces
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;
    
        return $this;
    }
    /**
     * Get datecreated
     *
     * @return \DateTime 
     */
    public function getDatecreated()
    {
        return $this->datecreated;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Annonces
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
     * @return Annonces
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
}