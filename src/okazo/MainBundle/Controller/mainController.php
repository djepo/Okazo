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
        $autoLocale = $this->get('okazo.tools')->getDefaultLanguage();        
        $url = $this->container->get('router')->generate('homepage', array('_locale' => $autoLocale));

        return new RedirectResponse($url);
    }
}