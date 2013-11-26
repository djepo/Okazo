<?php

namespace okazo\annoncesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Images
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="imageURL", type="string", length=255, nullable=true)
     */
    private $imageurl;

    /**
     * @Assert\File(maxSize="4M",
     *              mimeTypes = {"image/jpg", "image/jpeg", "image/gif", "image/png"})
     */
    private $file;

    /**
     * @var \Annonces
     *
     * @ORM\ManyToOne(targetEntity="Annonces", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="annonceId", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $annonce;
    
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
    public function setAnnonce(\okazo\annoncesBundle\Entity\Annonces $annonce = null) {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonceid
     *
     * @return \okazo\annoncesBundle\Entity\Annonces
     */
    public function getAnnonce() {
        return $this->annonce;
    }


    protected $path;

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

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        var_dump("Image preUpload (PrePersist et PreUpdate) <br>");

        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
            $this->setImageurl($this->getUploadDir() . '/' . $this->path);
            $this->setThumburl($this->getUploadDir() . '/thumbs/' . $this->path);
            //$this->path = $this->file->guessExtension();
            //var_dump($this->annonce);
        }
        //$this->get('test');
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a        
        $this->annonce = $this->annonce->getId();

        $this->file->move($this->getUploadRootDir(), $this->path);

        //create thumb
        $this->createThumbs($this->getAbsolutePath(), $this->getUploadRootDir() . "/thumbs/" . $this->path, 100);

        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove() {     
        //efface physiquement l'image
        if ($this->getImageurl()) {            
            unlink(__DIR__ . '/../../../../public_html/'.$this->getImageurl());
        }
        //efface physiquement l'image miniature
        if ($this->getThumburl()) {            
            unlink(__DIR__ . '/../../../../public_html/'.$this->getThumburl());
        }
    }

    function createThumbs($pathToImage, $pathToThumb, $thumbnail_width = 80, $thumbnail_height = 80) {
        //Create the thumbs directory if it not exists
        if (!file_exists($this->getUploadRootDir() . '/thumbs')) {
            mkdir($this->getUploadRootDir() . '/thumbs', 0777, true);
        }
   
        $arr_image_details = getimagesize($pathToImage);
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * ($new_width / $original_width));
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * ($new_height / $original_height));
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($pathToImage);
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, $pathToThumb);
        }
    }

}
