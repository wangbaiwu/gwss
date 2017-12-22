<?php
/**
 * Created by WangQi.
 * All Rights Reserved
 * Time: 17:30
 */

namespace Backend\DataBundle\DataFixtures\ODM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\UserBundle\Entity\User;


class LoadUserData implements FixtureInterface,ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $plainPassword='admin';
        $encoder = $this->container->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($userAdmin, $plainPassword);
        $userAdmin->setPassword($encodedPassword);
        $userAdmin->setIsActive(true);
        $manager->persist($userAdmin);
        $manager->flush();
    }
}