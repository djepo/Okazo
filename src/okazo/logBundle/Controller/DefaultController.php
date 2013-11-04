<?php

namespace okazo\logBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('okazologBundle:Default:index.html.twig', array('name' => $name));
    }
}
