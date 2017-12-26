<?php

namespace Backend\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Backend\UserBundle\Document\Product;

class DefaultController extends Controller
{
    /**
     * @Route("/admin",name="admin_main")
     */
    public function indexAction()
    {
        $info=$this->get('security.token_storage')->getToken()->getUser();
        $data=array(
            'title'=>'icsoc',
        );
        return $this->render('UiBundle:Default:index.html.twig',$data);
    }

    /**
     * @Route("/default",name="default")
     */
    public function defaultAction()
    {
        return new Response('default page');
    }

    /**
     * @Route("/foo",name="foo")
     */
    public function fooAction()
    {
        return new Response('Foo page');
    }

    /**
     * @Route("/bar",name="bar")
     */
    public function barAction()
    {
        return new Response('Bar page');
    }


}
