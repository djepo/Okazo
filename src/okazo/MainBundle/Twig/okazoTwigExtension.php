<?php
 
namespace okazo\MainBundle\Twig;
 
class okazoTwigExtension extends \Twig_Extension
{    
    protected $container;    
 
    public function __construct($container) {        
        $this->container=$container;
        
    }
 /*
    public function getFunctions()
    {
        return array(
            'parameters' => new \Twig_Function_Method($this, 'getParameters'),
        );
    }
 */
    public function getParameters()
    {
        /*
        $sql = "SELECT * FROM parameters LIMIT 1";
        $arrayParameters=$this->conn->fetchAll($sql);
        $parameters=$arrayParameters[0];
        
        return $parameters;
         * */
         
        return $this->container->get('okazo.parameters')->getParameters();
    }
 
    public function getGlobals()
    {
        return ['parameters' => $this->getParameters()];
    }
  
    public function getName()
    {
        return 'okazo_extension';
    }
}