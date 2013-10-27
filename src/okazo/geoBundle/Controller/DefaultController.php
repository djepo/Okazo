<?php

namespace okazo\geoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;  //pour le retour des réponses ajax

class DefaultController extends Controller
{    
    /**
     * Fonction permettant de trouver la ville la plus proche en fonction de ses coordonnées géographiques.
     * Appel Ajax uniquement, avec les paramètres lat et long pour la latitude et la longitude.
     * 
     * @Route("/Ajax/findCityByCoordinates/", name="ajaxFindCityByCoordinates")
     */
    public Function findCityByCoordinatesAction()
    {        
        if ($this->getRequest()->isXmlHttpRequest()) {             
            $request = $this->get('request');
            $latitude=$request->request->get('lat');
            $longitude=$request->request->get('long');
            
            $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager
            $resultatsRequete = $em->getRepository('okazogeoBundle:city')->cityByCoordinates($latitude, $longitude);
            
            Return new Response(json_encode($resultatsRequete),202);
            
        } else {            
            return new Response('Method Not Allowed',405);
        } 
    }
    
    /**
     * Fonction permettant de trouver une ville par son nom.
     * 
     * @Route("/Ajax/findCityByName", name="ajaxFindCityByName")
     */
    public function findCityByName()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {             
            $request = $this->get('request');
            $input=$request->query->get('startsWith');
            $maxRows=$request->query->get('maxRows');
            
            if(!$maxRows){$maxRows=10;}                  //si maxRows n'est pas défini, il est défini à 10
            if(!is_numeric($maxRows)) {$maxRows=10;}    //si maxRows n'est pas numérique, on le passe numérique et lui assigne la valeur 10
            if($maxRows<1){$maxRows=1;}                 //maxRows ne peut pas être inférieur à 1
               
            //on remplace les caractères spéciaux par des tirets (espace, apostrophe)
            $filter = array(" ", "'");
            $input = str_replace($filter, "-", $input);            
            
            $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager
            
            //Mettre l'appel à la requête de recherche des villes ici.
            //appel par code postal, ou par ville dans l'input.
            //Retour: pays, code postal, ville, coordonnées
            $resultatsRequete = $em->getRepository('okazogeoBundle:city')->cityByName($input, $maxRows);
            
            if (count($resultatsRequete)>0) {            
                Return new Response(json_encode($resultatsRequete),202);
            } else {
                return new Response('No Content',204);
            }
            
        } else {            
            return new Response('Method Not Allowed',405);
        } 
    }
    
    /**
     * Fonction affichant un template permettant de claculer la position géographique via des méthodes javascript en cas d'échec de la détection par ip du contrôleur principal
     * Le template redirigera automatiquement vers la page d'index une fois la détection achevée.
     * 
     * @Route("/Utilities/geoDetectionJS/", name="geoDetectionJS")
     */
    public function geoDetectionJS()
    {
       return $this->render('okazogeoBundle:utilities:geoDetection.html.twig'); 
    }
    
    /**
     * @Route("Ajax/findCity/", name=""ajaxFindCity)
     */
    /*public function findCity()
    {
        if ($this->getRequest()->isXmlHttpRequest()) { 
            $request = $this->get('request');
            $saisie=$request->request->get('saisie');
            
            Return new Response(json_encode($saisie),202);
            
       } else {
           return new Response('Method Not Allowed',405);
       }
    }*/
}
