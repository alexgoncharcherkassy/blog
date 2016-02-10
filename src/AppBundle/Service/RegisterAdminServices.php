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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RegisterAdminServices
{
    private $doctrine;
    private $service;


    public function __construct(RegistryInterface $doctrine, UserPasswordEncoder $service)
    {
        $this->doctrine = $doctrine;
        $this->service = $service;
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
        $password = $this->service->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setIsActive(true);

        $em->persist($user);
        $em->flush();

    }
}