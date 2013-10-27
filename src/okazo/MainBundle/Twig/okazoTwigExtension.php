<?php
 
namespace okazo\MainBundle\Twig;
 
class okazoTwigExtension extends \Twig_Extension
{
    private $em;
    private $conn;
 
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
        $this->conn = $em->getConnection();
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
        $sql = "SELECT * FROM parameters LIMIT 1";
        $arrayParameters=$this->conn->fetchAll($sql);
        $parameters=$arrayParameters[0];
        
        return $parameters;
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