<?php

namespace okazo\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;
use Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand;

class adminController extends Controller {
    public function indexAdminAction() {
        
        //$userManager = $this->get('fos_user.user_manager');
        //$users = $userManager->findUsers();
        
        //$roleHierarchy=$this->container->getParameter('security.role_hierarchy.roles');
        
        return $this->render('okazoMainBundle:admin:indexAdmin.html.twig');
    }
    
    public function blockUserAdminAction() {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        
        $roleHierarchy=$this->container->getParameter('security.role_hierarchy.roles');
        
        return $this->render('okazoMainBundle:admin:blockUserAdmin.html.twig', array('users'=>$users, 'roleHierarchy'=>$roleHierarchy));
        
    }
    
    public function BlockAdsAdminAction() {
        $request = $this->getRequest();                
        $page = $request->get('page', 1);
        if($page<1) {$page=1;}
        $em = $this->getDoctrine()->getManager();

        //$ads = $em->getRepository('okazoannoncesBundle:Annonces')->findAll();        
        
        $categoriesId=0;
        //$getPage=1;
        $getQuery="";
        $getLongitude=-1000;
        $getLatitude=-1000;
        $getDistance=null;
        $getSortBy="";
        $getSortDirection="";
        
        $ads = $em->getRepository('okazoannoncesBundle:Annonces')->getClassifieds($categoriesId, $page, $getQuery, $getLongitude, $getLatitude, $getDistance, $getSortBy, $getSortDirection);
        //var_dump($ads);
        return $this->render('okazoMainBundle:admin:blockAdsAdmin.html.twig', array('ads'=>$ads, 'page'=>$page));
    }
    
    public function clearCacheAction() {
        //$url=$this->generateUrl('homepage');
        $url = $this->container->get('request')->headers->get('referer');
        
        /*
        $input = new StringInput(null);
        $output = new NullOutput();
        
        $cacheClear = new CacheClearCommand();
        $cacheWarmup=new CacheWarmupCommand();
        $cacheClear->setContainer($this->container);
        $cacheWarmup->setContainer($this->container);
        
        session_write_close();
        
        $cacheClear->run($input, $output);
        $cacheWarmup->run($input, $output);
        */ 
        
        $envMode=$this->container->getParameter('kernel.environment');
        var_dump($envMode);
        
        $app_path = $this->container->getParameter('kernel.root_dir') . '/console' ;
        $process = new \Symfony\Component\Process\Process("php $app_path cache:clear --env=".$envMode." --quiet");
        $process->run();
        
        return $this->redirect($url);
    }
    

    public function testMailAction() {
        $message = \Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('contact@okazo.eu')
                    ->setTo('contact@okazo.eu')
                    ->setBody("Coucou !");
    
        $this->get('mailer')->send($message);

    return $this->render('okazoMainBundle:admin:indexAdmin.html.twig');
    }
    
}
