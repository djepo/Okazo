<?php

namespace okazo\geoBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;

class geo extends ContainerAware {

    protected $em;
    protected $request;
    protected $maxmind;             //maxming.geoip service
    protected $container;
    protected $Latitude;
    protected $Longitude;

    public function __construct($entityManager, $maxmind, $container) {        
        $this->em = $entityManager;        
        $this->maxmind = $maxmind;
        $this->container=$container;
        $this->request=$this->container->get('request');
    }

    private function computeCoordinates() {        
        if ($this->request->getHost() <> 'localhost') {  //si on est en local, on prends notre adresse ip, sinon, on prends l'adresse du client
            $ip = $this->request->getClientIp();
        } else {
            $ip = '78.228.105.2';
        }

        //on se sers du fichier de maxmind et de leur api php pour traduire l'ip en coordonnées géographiques
        $geoip = $this->maxmind->lookup($ip);
        if ($geoip) {
            $this->Latitude = $geoip->getLatitude();
            $this->Longitude = $geoip->getLongitude();
        } else {
            //mémorisation de l'adresse ip posant problème en log base de données
            if ($this->container->has('okazo.log')) {
                $this->container->get('okazo.log')->add("Adresse IP Non localisée dans okazo\MainBundle\Controller\findCoordinates()", "Erreur Localisation");
            }

            //renvoie -1000 en latitude et longitude pour indiquer un problème
            $this->Latitude = -1000;
            $this->Longitude = -1000;
        }
    }

    public function getLatitude() {
        if (empty($this->Latitude)) {
            $this->computeCoordinates();
        }
        return $this->Latitude;
    }

    public function getLongitude() {
        if (empty($this->Longitude)) {
            $this->computeCoordinates();
        }
        return $this->Longitude;
    }
}
