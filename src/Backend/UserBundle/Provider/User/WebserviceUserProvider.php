<?php
/**
 * Created by PhpStorm.
 * User: wangQi
 * All Rights Reserved
 * Time: 11:14
 */

namespace Backend\UserBundle\Provider\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;

class WebserviceUserProvider implements UserProviderInterface
{
    private $container;
    private $em;

    public function __construct(ContainerInterface $container,EntityManager $em)
    {
        $this->container=$container;
        $this->em=$em;
    }

    public function loadUserByUsername($username)
    {
        $userData=$this->em->getRepository('UserBundle:User')->findOneBy(array('username'=>$username));
        if ($userData) {
            $password = $userData->getPassword();
            $salt='';
            $roles=array('ROLE_USER');
            return new WebserviceUser($username, $password, $salt, $roles);
        }
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Backend\UserBundle\Provider\User\WebserviceUser';
    }
}