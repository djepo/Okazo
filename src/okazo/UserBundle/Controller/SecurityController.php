<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace okazo\UserBundle\Controller;

//define('FACEBOOK_APP_ID', '125464904285037');
//define('FACEBOOK_SECRET', 'a68aed6d2995c132eee76f61c9263285');

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    protected $fbAppID;
    protected $fbAppSecret;
    //protected $facebook;
    //protected $container;
    
    /**
     * Set the Application ID.
     *
     * @param string $appId The Application ID
     * @return BaseFacebook
     */
    public function setAppId($appId) {
        $this->appId = $appId;
        return $this;
    }

    /**
     * Get the Application ID.
     *
     * @return string the Application ID
     */
    public function getAppId() {
        return $this->appId;
    }

    /**
     * Set the App Secret.
     *
     * @param string $appSecret The App Secret
     * @return BaseFacebook
     */
    public function setAppSecret($appSecret) {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * Get the App Secret.
     *
     * @return string the App Secret
     */
    public function getAppSecret() {
        return $this->appSecret;
    }

    /**************************************************************************/
    
    public function __construct()
    {
        //$this->facebook=BaseFacebook;        
        //$this->setAppId($config['appId']);
        //$this->setAppSecret($config['secret']);
        //$container= ContainerAware;
        //$test=$this->container->getParameter('facebookAppID');
        //$this->container->getParameter($container);
    }
    
    
    public function loginAction() {   
        
        $request = $this->container->get('request');        
        $session = $request->getSession();        

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);            
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        
        //Gestion des affichages erreurs
        if ($error!='') {
            $this->get('session')->getFlashBag()->add('error', $error); 
            $error='';  //override de la variable $error pour que le layout fosuser ne l'affiche pas.
        }        
        
        $csrfToken = $this->container->has('form.csrf_provider')? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate'): null;
        /*
        return $this->renderLogin(array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'csrf_token' => $csrfToken,
        ));         
         */
        return $this->render('okazoUserBundle:Security:login.html.twig', array('last_username' => $lastUsername,
                                                                               'error'=>$error,
                                                                               'csrf_token'=>$csrfToken));
    }

    /**
     * Controleur du block de connexion utilisateur, en haut de page
     * @param string $mode Mode de rendu: render_block (ou rien) pour faire un rendu du bloc seul. render_whole pour recharger la page complète, et retourner à l'accueil.
     * @Route(path="/user/login/{mode}", name="block_user_security_check")
     **/
    public function blockLoginAction($mode="render_block") {
        $request = $this->container->get('request');        
        $session = $request->getSession();        

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        
        //Gestion des affichages erreurs
        if ($error!='') {
            $this->get('session')->getFlashBag()->add('error', $error); 
            $error='';  //override de la variable $error pour que le layout fosuser ne l'affiche pas.
        }
        
        if ($mode==="render_whole") {            
            return $this->redirect($this->generateUrl('homepage'),301);
            /*return $this->render('okazoMainBundle:default:index.html.twig', array('last_username' => $lastUsername,
                        'error' => $error,
                        'csrf_token' => $csrfToken,
            ));             
             */
            
        } else {
            return $this->render('okazoUserBundle:blocks:userLogin.html.twig', array('last_username' => $lastUsername,
                        'error' => $error,
                        'csrf_token' => $csrfToken,
            ));
        }
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data) {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction() {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction() {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * @Route("/facebook/registration", name="facebookRegistration")
     */
    public function facebookRegistration() {
        $this->fbAppID=$this->container->getParameter('facebookAppID');
        $this->fbAppSecret=$this->container->getParameter('facebookAppSecret');

        if ($_REQUEST) {
            echo '<p>signed_request contents:</p>';
            $response = $this->parse_signed_request($_REQUEST['signed_request'], $this->fbAppSecret);            
            echo '<pre>';
            print_r($response);
            echo '</pre>';
        } else {
            echo '$_REQUEST is empty';
        }
    }

    function parse_signed_request($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

}

