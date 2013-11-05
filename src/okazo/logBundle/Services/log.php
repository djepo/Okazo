<?php

namespace okazo\logBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;

class log extends ContainerAware {
    
    protected $em;
    protected $request;
    
    public function __construct($entityManager, $request) {
        $this->em = $entityManager;
        $this->request = $request;
    }
    
    public function add($message, $categorie='') {
        $ip=$this->request->getClientIp();
        $uri=$this->request->getUri();
        $userAgent=$_SERVER[ "HTTP_USER_AGENT" ];
        $source='SERVER';
        
        //var_dump('getHost: '.$this->request->getHost().'<br>');
        
        $sql = "INSERT INTO `log`(`timestamp`, `ip`, `userAgent`, `uri`, `message`, `source`, `categorie`, `seen`) VALUES ("
                . "'".date('Y-m-d H:i:s')."',"
                ."'".$ip."',"
                ."'".$userAgent."',"
                ."'".$uri."',"
                ."'".$message."',"
                ."'".$source."',"                
                ."'".$categorie."',"
                ."'false')";                
        
        $this->em->getConnection()->executeUpdate($sql);
    }
}

