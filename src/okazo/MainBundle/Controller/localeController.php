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

class localeController extends Controller {    
    /**
     * @route("/setLocale/{locale}", name="setLocale")
     */
    public function setLocaleAction($locale = null) {                
        if($locale != null)
        {
            // On enregistre la langue en session            
            $this->get('session')->set('_locale', $locale);
            //$request=$this->getRequest();            
            //$request->setLocale($locale);
        }    

        // on tente de rediriger vers la page d'origine
        //$url = $this->container->get('request')->headers->get('referer'); //problème ici, si on redirige vers la page appelante, on garde la locale, car elle est dans l'adresse... on passe donc l'url à null pour rediriger vers la homepage avec la locale en paramètres
        if(empty($url)) {
           $url = $this->container->get('router')->generate('homepage', array('_locale'=>$this->get('session')->get('_locale')));           
        }
        return new RedirectResponse($url); 
    }
}
