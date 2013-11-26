<?php

namespace okazo\MainBundle\Services;
use Doctrine\ORM\EntityManager;

class parameters {
    
    protected $conn;
    protected $parameters;

    public function __construct($conn) {
        $this->conn = $conn;        
    }

    public function getParameters() {
        
        if (empty($this->parameters)){                        
            $sql = "SELECT * FROM parameters LIMIT 1";
            $arrayParameters=$this->conn->fetchAll($sql);
            $this->parameters=$arrayParameters[0];            
        }
        
        return $this->parameters;    
    }
    
    public function getParameterValue($name) {
         if (empty($this->parameters)){     
             $this->getParameters();
         }
         
         foreach ($this->parameters as $parameterName=>$parameterValue) {
             if(strtolower($parameterName)==strtolower($name)) {
                 return $parameterValue;
             }
         }
         
         throw new \Exception('Paramètre "'.$name.'" introuvable.');
         
    }
}
