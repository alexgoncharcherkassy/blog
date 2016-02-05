<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 05.02.16
 * Time: 17:38
 */

namespace AppBundle\Service;


use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegisterAdminServices
{
    private $doctrine;
    private $container;


    public function __construct(RegistryInterface $doctrine, ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    public function registerAdmin($firstName, $lastName, $email, $plainPassword)
    {
        $em = $this->doctrine->getManager();

        $user = new User();

        $user->setUsername('admin');
        $user->setRoles('ROLE_ADMIN');
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $password = $this->container->get('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setIsActive(true);

        $em->persist($user);
        $em->flush();

    }
}