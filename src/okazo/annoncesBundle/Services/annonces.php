<?php

namespace okazo\annoncesBundle\Services;

class annonces {

    protected $usersMediaPath;
    protected $rootPath;
    protected $em;
    protected $categories;

    public function __construct($usersMediaPath, $rootPath, $em) {
        $this->usersMediaPath = $usersMediaPath;
        $this->rootPath = $rootPath;
        $this->em = $em;
    }

    /**
     * Compute the real path of a media with a given url
     * @param string $url
     * @return real path, or NULL if an error occurs
     */
    public function computeRealMediaPath($url = "") {
        try {
            $arr = explode("/", $url);
            if (count($arr) > 0) {
                $fileName = $arr[count($arr) - 1];

                return $this->rootPath . "/" . $this->usersMediaPath . "/" . $fileName;
            } else {
                return NULL;
            }
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Delete pictures given in array parameter with and http path
     * @param array $pictures
     */
    public function deletePictures($pictures) {
        foreach ($pictures as $picture) {
            //var_dump($picture);
            $picturesPath[] = $this->computeRealMediaPath($picture['thumbURL']);
            $picturesPath[] = $this->computeRealMediaPath($picture['imageURL']);
        }

        foreach ($picturesPath as $picture) {
            if (file_exists($picture)) {
                unlink($picture);
            }
        }
    }

    /**
     * Delete a classified with a given id
     * @param string $id id of the classified
     * @return array with code error and message
     */
    public function deleteClassified($id) {
        if ($id != null) {
            //$em = $this->getEntityManager();

            $classified = $this->em->getRepository('okazoannoncesBundle:Annonces')->find($id);
            $this->em->remove($classified);
            $this->em->flush();
            
            return array('CODE' => 'Success', 'Message' => 'TEST - Annonce ' . $id . ' correctement Supprimée de la base');  //annonce correctement supprimée de la base
            
            if ($classified) {
                //recherche des éventuelles images liées à l'annonce
                $pictures = $this->em->getConnection()->executeQuery("SELECT images.* FROM images WHERE images.annonceId='" . $id . "'")->fetchAll();
                if ($pictures) {
                    $this->deletePictures($pictures);
                }

                //l'annonce existe, on va donc la supprimer physiquement de la base.
                //Les images correspondantes seront automatiquement effacées grâce aux contraintes sql
                $return = $this->em->getConnection()->executeUpdate("DELETE annonces.* FROM annonces WHERE annonces.id='" . $id . "'");
                if ($return) {
                    return array('CODE' => 'Success', 'Message' => 'Annonce ' . $id . ' correctement Supprimée de la base');  //annonce correctement supprimée de la base
                } else {
                    return array('CODE' => 'Error', 'Message' => "Erreur lors de la suppression de l'annonce " . $id);
                }
            } else {
                return array('CODE' => 'Error', 'Message' => 'Classified ' . $id . ' not found.');
            }
        } else {
            return array('CODE' => 'Error', 'Message' => 'You must specify an id.');   //erreur en cas d'identifiant non spécifié
        }
    }

    /**
     *
     * @param boolean $hideInexistent Hide non existent categories (default=true)
     * @param type $refresh Refresh the list if this one exists (default=false)
     * @return array of categories, ordered
     */
    public function getCategories($hideInexistent = true, $refresh = false) {
        //$em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager
        if (empty($this->categories) || $refresh == true) {
            $resultatsRequete = $this->em->getRepository('okazoannoncesBundle:Annonces')->getCategoriesExistantes($hideInexistent);
            $this->categories = $resultatsRequete["categories"];
        }
        return $this->categories;
    }

}
