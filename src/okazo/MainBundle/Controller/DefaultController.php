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

    private $computedLatitude;
    private $computedLongitude;

    public function findCoordinates()
    {
        $request = $this->getRequest();

        if ($request->getHost() <> 'localhost') {  //si on est en local, on prends notre adresse ip, sinon, on prends l'adresse du client
                $ip = $request->getClientIp();
            } else {
                $ip = '78.228.105.2';
            }
            //on se sers du fichier de maxmind et de leur api php pour traduire l'ip en coordonnées géographiques
            $geoip = $this->get('maxmind.geoip')->lookup($ip);
            if ($geoip) {
                $this->computedLatitude = $geoip->getLatitude();
                $this->computedLongitude = $geoip->getLongitude();
            } else {
                $this->computedLatitude = -1000;
                $this->computedLongitude = -1000;
            }
    }
    
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

        //Détection des Robots
        //(contient bot, spider ou yahoo dans le user agent)
        $crawler = 0;
        if ( preg_match('/(bot|spider|yahoo)/i', $_SERVER[ "HTTP_USER_AGENT" ] )) {
            $crawler = 1 ;
            $logger = $this->get('logger');
            $logger->info('Robot d\'exploration: '.$_SERVER[ "HTTP_USER_AGENT" ]);
        }
        //Fin de détection des robots
        
        //Ajouter ici des fonctions de normalisation des paramètres get
        if (!$getPage) {
            $getPage = 1;
        }
        if ($getPage < 1) {
            $getPage = 1;
        }

        //Normalisation Distance
        if(empty($getDistance)) {$getDistance=0;}
        if($crawler===1){$getDistance=50000;}    //Si c'est un robot qui visite, on affiche toutes les annonces de la terre

        //Nouvelle version de calcul des coordonnées, centralisée
        //if ($getLatitude == -1000 || $getLongitude == -1000 || empty($getLatitude) || empty($getLongitude)) {
        if (empty($getLatitude) || empty($getLongitude)) {
            $this->findCoordinates();
            $getLatitude=$this->computedLatitude;
            $getLongitude=$this->computedLongitude;
        }
        //Fin Nouvelle version de calcul des coordonnées, centralisée
        $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager
        $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getCategoriesExistantes();
        $listeCategories = $resultatsRequete["categories"];
/*
        $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager

        $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getCategoriesExistantes();
        $listeCategories = $resultatsRequete["categories"];

        //Normalisation catégories
        if (!isset($getCategorieId)) {
            $categorieId = 0;
        } elseif (empty($getCategorieId)) {
            $categorieId = 0;
        } elseif (!is_numeric($getCategorieId)) {
            $categorieId = 0;
        } else {
            $categorieId = $getCategorieId;
        }
        //si une catégorie sélectionnée est parente, on va chercher toutes les catégories filles
        foreach ($listeCategories as $categorieParente) {
            if ($categorieParente['id'] == $categorieId) { //on recherche la catégorie dans la liste
                if ($categorieParente['categorie_parente_id'] === \NULL) { //on vérifie si on a sélectionné une catégorie principale (pas de parents)                    
                    foreach ($listeCategories as $categorieFille) {
                        if ($categorieFille['categorie_parente_id'] == $categorieParente['id']) {
                            $listeCategoriesId[] = $categorieFille['id'];
                        }
                    }
                }
            }
        }
        if (!isset($listeCategoriesId)) {        //si on a une catégorie parente sans catégorie fille, on va chercher la catégorie parente directement.
            $listeCategoriesId[] = $getCategorieId;
        }      
*/
        /*$resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getClassifieds($listeCategoriesId, $getPage, $getQuery, $getLongitude, $getLatitude, $getDistance, $getSortBy, $getSortDirection);*/
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
        
        /*
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Test type inconnu'
        );
        $this->get('session')->getFlashBag()->add(
            'warning',
            'Test warning blablablablablabla blablablablablablablablablablablablablablabla blablablablablablablablablablablabla '
        );
                $this->get('session')->getFlashBag()->add(
            'success',
            "Ouvrier expérimenté dans le nettoyage des tombes, je vous propose de vous rencontrer à l'endroit où se situe la tombe à rénover pour vous exposer un devis clair et gratuit. respect et confidentialité asurée, paiement en cheque emploi service. satisfaction garantie. "
        );
                        $this->get('session')->getFlashBag()->add(
            'error',
            'Test warning blablablablablabla blablablablablablablablablablablablablablabla blablablablablablablablablablablabla '
        );
        */
        
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

        //$serializer = new Serializer(array(),array('json' => new JsonEncoder(), 'xml' => new XmlEncoder()));
        //$json_encoded_data = $serializer->encode($annonces,'json');
        //$xml_encoded_data = $serializer->encode($annonces,'xml');
        //$response = new Response($xml_encoded_data);
        //return $response;
    }

    /**
     * @Route("categoriesList", name="categoriesList")
     * @Template()
     */
    public function categoriesListAction() {
        $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager

        $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getCategoriesExistantes();
        $listeCategories = $resultatsRequete["categories"];

        //Nouvelle version de calcul des coordonnées, centralisée
        $this->findCoordinates();
        //$getLatitude=$this->computedLatitude;
        //$getLongitude=$this->computedLongitude;
        //Fin Nouvelle version de calcul des coordonnées, centralisée

        return $this->render("okazoannoncesBundle:pages:categoriesList.html.twig", array(   'listeCategories'=>$listeCategories,
                                                                                            'getLatitude' => $this->computedLatitude,
                                                                                            'getLongitude' => $this->computedLongitude,
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
