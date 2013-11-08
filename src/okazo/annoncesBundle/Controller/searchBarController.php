<?php

namespace okazo\annoncesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class searchBarController extends Controller {
    /**
     * @Route("/blocks/displaySearchBar", name="displaySearchBar")     
     */
    public function displaySearchBarAction() {
        $request = $this->getRequest();        
        $getQuery = $request->get('q');
        $getDistance = $request->get('distance', 30);        
        $getLatitude = $request->get('lat', null);
        $getLongitude = $request->get('long', null);
        $getCategorieId = $request->get('cat', 0);

        //Normalisation Distance
        if (empty($getDistance)) {
            $getDistance = 0;
        }

        //détection des robots
        if ($this->get('okazo.tools')->isBot() === true) {
            $this->container->get('okazo.log')->add("Exploration par Robot détectée dans okazo\MainBundle\Controller\indexAction()", "Exploration par robot");
            $getDistance = 50000;
        }    //Si c'est un robot qui visite, on affiche toutes les annonces de la terre
        
        //Nouvelle version de calcul des coordonnées, centralisée                
        if (empty($getLatitude) || empty($getLongitude)) {            
            $getLatitude=$this->get('okazo.geo')->getLatitude();
            $getLongitude=$this->get('okazo.geo')->getLongitude();
        }
        //Fin Nouvelle version de calcul des coordonnées, centralisée

        $listeCategories = $this->get('okazo.annonces')->getCategories();

        /**
         * Gestion du retour
         */
        return $this->render('okazoannoncesBundle:blocks:search.html.twig', array(
                    'getDistance' => $getDistance,
                    'getQuery' => $getQuery,
                    'getLatitude' => $getLatitude,
                    'getLongitude' => $getLongitude,
                    'listeCategories' => $listeCategories,
                    'categorieId' => $getCategorieId,
                        )
        );
    }
}
