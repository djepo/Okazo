<?php

namespace okazo\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class errorsController extends Controller {
    public function quatreCentQuatreAction() {
        throw $this->createNotFoundException();
    }    
}
