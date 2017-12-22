<?php

namespace Backend\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Backend\DataBundle\Document\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/data")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/",name="data_index")
     */
    public function indexAction()
    {
        return $this->render('DataBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create")
     * @return Response
     */
    public function createAction()
    {
        $product=new Product();
        $product->setName('lee');
        $product->setPrice('19.99');
        $dm=$this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();
        return new Response('Created product id '.$product->getId());
    }

    /**
     * @Route("/show/{id}",name="data_show",requirements={"id":"\w+"},defaults={"id":1})
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $product=$this->get('doctrine_mongodb')->getRepository('DataBundle:Product')->find($id);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        return new Response('Created product id '.$product->getPrice());
    }

    /**
     * @Route("/update/{id}",name="data_update",requirements={"id":"\w+"},defaults={"id":1})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($id)
    {
        $dm=$this->get("doctrine_mongodb")->getManager();
        $product=$dm->getRepository('DataBundle:Product')->find($id);
        if(!$product)
        {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        $product->setName('New product name');
        $dm->flush();
        return $this->redirect($this->generateUrl('data_show',array('id'=>$id)));
    }

    /**
     * @Route("/delete/{id}")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $dm=$this->get('doctrine_mongodb')->getManager();
        $product=$dm->getRepository('DataBundle:Product')->find($id);
        if(!$product)
        {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        $dm->remove($product);
        $dm->flush();
        return new Response('suc');
    }

    /**
     * @Route("/query")
     * @return Response
     */
    public function queryAction()
    {
        /** @var \Doctrine\MongoDB\Query\Builder $qb */
        $qb=$this->get('doctrine_mongodb')->getManager()->createQueryBuilder('DataBundle:Product');
        $products=$qb->field('name')->equals('lee')
                                    ->hydrate(false)
                                    ->limit(2)
                                    ->sort('price','DESC')
                                    //->distinct('name')
                                    ->getQuery()
                                    //->getSingleResult();
                                    ->execute();

        var_dump($products);
        foreach($products as $v)
        {
            var_dump($v);
        }
        die;
        return $products;
    }


}
