<?php

namespace okazo\toolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('okazotoolsBundle:Default:index.html.twig', array('name' => $name));
    }
}
