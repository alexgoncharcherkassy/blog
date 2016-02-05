<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 05.02.16
 * Time: 17:33
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('register:admin')
            ->setDescription('Register Admin User')
            ->addArgument(
                'firstName',
                InputArgument::REQUIRED,
                'First Name?'
            )
            ->addArgument(
                'lastName',
                InputArgument::REQUIRED,
                'Last Name?'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Email?'
            )
            ->addArgument(
                'plainPassword',
                InputArgument::REQUIRED,
                'Password?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('plainPassword');
        if ($firstName && $lastName && $email && $plainPassword) {
            $this->getContainer()->get('app.register.admin')
                ->registerAdmin($firstName, $lastName, $email, $plainPassword);
            $text = 'Success record';
        } else {
            $text = 'Fail record';
        }

        $output->writeln($text);
    }
}
