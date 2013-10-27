<?php

namespace okazo\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class mainController extends Controller {    
    /**
     * Point d'entrée principal du site.
     * Lance une détection de la locale automatiquement
     * et redirige vers la page d'accueil avec la locale renseignée automatiquement.
     * @route("/", name="entryPoint")
     */
    public function entryPointAction() {
        $autoLocale=$this->getDefaultLanguage();
        $url = $this->container->get('router')->generate('homepage', array('_locale'=>$autoLocale));
        
        return new RedirectResponse($url); 
    }
    
   
   /* Fonctions de détection de la langue préférée de l'utilisateur */
    
   function getDefaultLanguage() {
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
        return $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    else
        return $this->parseDefaultLanguage(NULL);
   }    
    
    function parseDefaultLanguage($http_accept) {
        $deflang=$this->container->getParameter('locale'); //tente de récupérer la locale dans le fichier parameter.yml
        if (!$deflang) {$deflang="en"; }
        
       if(isset($http_accept) && strlen($http_accept) > 1)  {
          # Split possible languages into array
          $x = explode(",",$http_accept);
          foreach ($x as $val) {
             #check for q-value and create associative array. No q-value means 1 by rule
             if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i",$val,$matches))
                $lang[$matches[1]] = (float)$matches[2];
             else
                $lang[$val] = 1.0;
          }

          #return default language (highest q-value)
          $qval = 0.0;
          foreach ($lang as $key => $value) {
             if ($value > $qval) {
                $qval = (float)$value;
                $deflang = $key;
             }
          }
       }
       return strtolower($deflang);
    }
}
