<?php

namespace okazo\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Security\Core\SecurityContext;    //Pour la connexion utilisateur
use Symfony\Component\HttpFoundation\RedirectResponse;

//use okazo\geoBundle\PositionDetection;
//use okazo\annoncesBundle\Entity\annonces;

/**
 * @Route("/{_locale}/", defaults={"_locale"="en"}, requirements={"fr","en"})
 */
class DefaultController extends Controller {

    //private $computedLatitude;
    //private $computedLongitude; 

    /**
     * @Route("home/", name="homepage")
     * @Template()
     */
    public function indexAction() {
        $request = $this->getRequest();
        $getPage = $request->get('page', 1);
        $getQuery = $request->get('q');
        $getDistance = $request->get('distance', 30);
        $getSortBy = $request->get('sortby');
        $getSortDirection = $request->get('sortdirection');
        $getLatitude = $request->get('lat', null);
        $getLongitude = $request->get('long', null);
        $getCategorieId = $request->get('cat', 0);
                
        //Fin de détection des robots
        //Ajouter ici des fonctions de normalisation des paramètres get
        if (!$getPage) {
            $getPage = 1;
        }
        if ($getPage < 1) {
            $getPage = 1;
        }

        //Normalisation Distance
        if (empty($getDistance)) {
            $getDistance = 0;
        }
        if ($this->get('okazo.tools')->isBot() === true) {
            if ($this->get('okazo.parameters')->getParameterValue('logBots')==true) {
                $this->container->get('okazo.log')->add("Exploration par Robot détectée dans okazo\MainBundle\Controller\indexAction()", "Exploration par robot");
            }
            $getDistance = 50000;
        }    //Si c'est un robot qui visite, on affiche toutes les annonces de la terre
        //   
        //Nouvelle version de calcul des coordonnées, centralisée        
        if (empty($getLatitude) || empty($getLongitude)) {            
            $getLatitude=$this->get('okazo.geo')->getLatitude();
            $getLongitude=$this->get('okazo.geo')->getLongitude();
        }
        //Fin Nouvelle version de calcul des coordonnées, centralisée
        
        $listeCategories = $this->get('okazo.annonces')->getCategories();

        $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager
        $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getClassifieds($getCategorieId, $getPage, $getQuery, $getLongitude, $getLatitude, $getDistance, $getSortBy, $getSortDirection);

        $annonces = $resultatsRequete["annonces"];
        $nombrePages = $resultatsRequete["nombrePages"];
        $nombreAnnonces = $resultatsRequete["nombreAnnonces"];
        $page = $resultatsRequete["page"];
        $sortDirectionPrix = "ASC";
        $sortDirectionDistance = "ASC";
        switch ($getSortBy) {
            case "prix":
                if ($getSortDirection == "ASC") {
                    $sortDirectionPrix = "DESC";
                } else {
                    $sortDirectionPrix = "ASC";
                }
                break;
            case "distance":
                if ($getSortDirection == "ASC") {
                    $sortDirectionDistance = "DESC";
                } else {
                    $sortDirectionDistance = "ASC";
                }
                break;
        }

        /**
         * Gestion du retour
         */
        return $this->render('okazoMainBundle:default:index.html.twig', array('annonces' => $annonces,
                    'nombreAnnonces' => $nombreAnnonces,
                    'nombrePages' => $nombrePages,
                    'page' => $page,
                    'getDistance' => $getDistance,
                    'getQuery' => $getQuery,
                    'getSortBy' => $getSortBy,
                    'getSortDirection' => $getSortDirection,
                    'sortDirectionPrix' => $sortDirectionPrix,
                    'sortDirectionDistance' => $sortDirectionDistance,
                    'getLatitude' => $getLatitude,
                    'getLongitude' => $getLongitude,
                    'listeCategories' => $listeCategories,
                    'categorieId' => $getCategorieId,
                        )
        );
    }

    /**
     * @Route("categoriesList", name="categoriesList")
     * @Template()
     */
    public function categoriesListAction() {
        $listeCategories = $this->get('okazo.annonces')->getCategories();

        return $this->render("okazoannoncesBundle:pages:categoriesList.html.twig", array('listeCategories' => $listeCategories,
                    'getLatitude' => $this->get('okazo.geo')->getLatitude(),
                    'getLongitude' => $this->get('okazo.geo')->getLongitude(),
                    'getDistance' => "30"));
    }

    /**
     *
     * Affichage de la page stipulant que Javascript est obligatoire
     */
    public function noScriptAction() {
        return $this->render("okazoMainBundle:pages:noScript.html.twig");
    }

    public function testAction() {
        return $this->render("okazoMainBundle:pages:test.html.twig");
    }

}
