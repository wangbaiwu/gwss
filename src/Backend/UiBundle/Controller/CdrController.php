<?php

namespace Backend\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


class CdrController extends Controller
{
    /**
     * @Route("/cdr",name="cdr_main")
     */
    public function indexAction()
    {
        $data=[];
        return $this->render('UiBundle:Default:cdr.html.twig',$data);
    }



}
