<?php

namespace okazo\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\HttpFoundation\RedirectResponse;

class ajaxController extends Controller {

    public function ajaxChangeParameterAction() {
        $request = $this->get('request');
        if ($request->isXMLHttpRequest()) {

            $parameterName = $request->request->get('parameter');
            $parameterValue = $request->request->get('value');

            $em = $this->getDoctrine()->getManager();

            //the update request
            $sql = "UPDATE parameters SET " . $parameterName . "=" . $parameterValue;
            $em->getConnection()->executeUpdate($sql);

            //in this part, we will load the value again to check  if the database updated it well
            $sql = "SELECT " . $parameterName . " FROM parameters LIMIT 1";
            $arrayParameters = $em->getConnection()->fetchAll($sql);
            $parameterValue = $arrayParameters[0][$parameterName];

            $return = array("parameter" => $parameterName, "value" => $parameterValue);

            return new Response(json_encode($return), 200);
        } else {
            //only ajax calls are accepted
            return new Response('', 405);
        }
    }

    public function ajaxChangeClassifiedsPageAction() {
        $request = $this->get('request');
        if ($request->isXMLHttpRequest()) {
            $page = $request->request->get('page');
            if ($page < 1) {
                $page = 1;
            }
            $em = $this->getDoctrine()->getManager();

            $categoriesId = 0;
            $getQuery = "";
            $getLongitude = -1000;
            $getLatitude = -1000;
            $getDistance = null;
            $getSortBy = "";
            $getSortDirection = "";

            $ads = $em->getRepository('okazoannoncesBundle:Annonces')->getClassifieds($categoriesId, $page, $getQuery, $getLongitude, $getLatitude, $getDistance, $getSortBy, $getSortDirection);

            return $this->render('okazoadminBundle:blocks:blockAdsAdmin.html.twig', array('ads' => $ads, 'page' => $page));
        } else {
            //only ajax calls are accepted
            return new Response('', 405);
        }
    }

    public function ajaxDeleteClassifiedAction() {
        $request = $this->get('request');
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id', null);

            //if no id, then, throw a http 400 error (Bad Request)
            if ($id == null) {
                return new response('You must provide and Id.', 400);
            }

            $return=$this->get('okazo.annonces')->deleteClassified($id);            

            if ($return["CODE"] == "Error") {
                return new response($return["Message"], 404);  //si il y a un retour, c'est qu'il y a une erreur
            } else {
                return new response($return["Message"], 200);
            }
        } else {
            //only ajax calls are accepted
            return new Response('only Ajax calls are allowed.', 405);
        }
    }

}
