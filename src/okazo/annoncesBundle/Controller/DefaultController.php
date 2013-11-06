<?php

namespace okazo\annoncesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;  //pour le retour des réponses ajax

class DefaultController extends Controller {

    /**
     * @Route("/Ajax/Paginator", name="ajaxPaginator")
     */
    public function paginatorAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager(); //initialisation de l'entitymanager

            $request = $this->get('request');
            $getLatitude = $request->request->get('lat', -1000);
            $getLongitude = $request->request->get('long', -1000);
            $getDistance = $request->request->get('distance');
            $getPage = $request->request->get('page', 1);
            $getQuery = $request->request->get('q');

            //Ajouter ici des fonctions de normalisation des paramètres get
            if (!$getPage) {
                $getPage = 1;
            }
            if ($getPage < 1) {
                $getPage = 1;
            }

            if ($getLatitude == -1000 || $getLongitude = -1000) {
                if ($request->getHost() <> 'localhost') {
                    $ip = $request->getClientIp();
                } else {
                    $ip = '78.228.105.2';
                }

                $geoip = $this->get('maxmind.geoip')->lookup($ip);
                $getLatitude = $geoip->getLatitude();
                $getLongitude = $geoip->getLongitude();
            }

            $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getClassifiedsPaginator($getPage, $getQuery, $getLongitude, $getLatitude, $getDistance);

            Return new Response(json_encode($resultatsRequete), 200);
        } else {
            return new Response('Method Not Allowed', 405);
        }
    }

    /**
     * @Route("/Ajax/Categories", name="listeCategoriesExistantes")
     */
    public function listeCategoriesAction() {
        $categories = $this->get('okazo.annonces')->getCategories();

        $response = new Response(json_encode($categories));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

        //Return new Response(json_encode($categories),200);
    }

}
