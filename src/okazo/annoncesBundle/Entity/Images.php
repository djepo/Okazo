<?php

namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Images
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 */
class Images {

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
     * @ORM\Column(name="thumbURL", type="string", length=255, nullable=true)
     */
    private $thumburl;

    /**
     * @var string
     *
     * @ORM\Column(name="imageURL", type="string", length=255, nullable=false)
     */
    private $imageurl;
    
     /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var \Annonces
     *
     * @ORM\ManyToOne(targetEntity="Annonces", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="annonceid", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $annonceid;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set thumburl
     *
     * @param string $thumburl
     * @return Images
     */
    public function setThumburl($thumburl) {
        $this->thumburl = $thumburl;

        return $this;
    }

    /**
     * Get thumburl
     *
     * @return string
     */
    public function getThumburl() {
        return $this->thumburl;
    }

    /**
     * Set imageurl
     *
     * @param string $imageurl
     * @return Images
     */
    public function setImageurl($imageurl) {
        $this->imageurl = $imageurl;

        return $this;
    }

    /**
     * Get imageurl
     *
     * @return string
     */
    public function getImageurl() {
        return $this->imageurl;
    }

    /**
     * Set annonceid
     *
     * @param \okazo\annoncesBundle\Entity\Annonces $annonceid
     * @return Images
     */
    public function setAnnonceid(\okazo\annoncesBundle\Entity\Annonces $annonceid = null) {
        $this->annonceid = $annonceid;

        return $this;
    }

    /**
     * Get annonceid
     *
     * @return \okazo\annoncesBundle\Entity\Annonces
     */
    public function getAnnonceid() {
        return $this->annonceid;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../public_html/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/users/media';
    }

}
