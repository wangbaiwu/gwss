<?php
/**
 * Created by PhpStorm.
 * User: wangQi
 * All Rights Reserved
 * Time: 13:57
 */
namespace Backend\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Backend\UserBundle\Entity\User;


/**
 * @Route("/")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UiBundle:Security:login.html.twig',[
            'title'=>'Login',
            'username'=>$lastUsername,
            'error'=>$error,
        ]);

    }

    /**
     * @Route("/logout",name="logout")
     * @return Response
     */
    public function logoutAction()
    {
    }


}