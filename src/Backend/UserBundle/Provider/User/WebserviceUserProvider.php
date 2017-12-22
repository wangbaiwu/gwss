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

class WebserviceUserProvider implements UserProviderInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container=$container;
    }

    public function loadUserByUsername($username)
    {
        $dm=$this->container->get('doctrine_mongodb')->getManager();
        $userData=$dm->getRepository('UserBundle:User')->findOneBy(array('username'=>$username));
        //验证分机 TODO:upgrade
        $no=$this->container->get('request_stack')->getCurrentRequest()->request->get('_agentPhone');
        if($no=intval($no))
        {
            $this->container->get('request_stack')->getCurrentRequest()->getSession()->set('_agentPhone',$no);
        }
        else
        {
            $no=$this->container->get('request_stack')->getCurrentRequest()->getSession()->get('_agentPhone');
        }
        $noData=$dm->getRepository('DataBundle:Extension')->findOneBy(array('no'=>intval($no),'isActive'=>true));

        if ($userData && $noData) {
            $password = $userData->getPassword();
            $salt='';
            $roles=array('ROLE_USER');
            return new WebserviceUser($username, $password, $salt, $roles, $no);
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
        return $class === 'Cscc\UserBundle\Provider\User\WebserviceUser';
    }
}