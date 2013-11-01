<?php

namespace okazo\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\RedirectResponse;

class ajaxController extends Controller {

    public function ajaxChangeParameterAction() {
        $request = $this->get('request');
        if ($request->isXMLHttpRequest()) {
            
            $parameterName=$request->request->get('parameter');
            $parameterValue=$request->request->get('value');

            $em = $this->getDoctrine()->getManager();

            //the update request
            $sql = "UPDATE parameters SET ".$parameterName."=".$parameterValue;            
            $em->getConnection()->executeUpdate($sql);
            
            //in this part, we will load the value again to check  if the database updated it well
            $sql = "SELECT ".$parameterName." FROM parameters LIMIT 1";
            $arrayParameters=$em->getConnection()->fetchAll($sql);
            $parameterValue=$arrayParameters[0][$parameterName];
            
            $return=array("parameter"=>$parameterName, "value"=>$parameterValue);
            
            return new Response(json_encode($return),200);

        } else {
            //only ajax calls are accepted
            return new Response('',405);
        }
    }

}
