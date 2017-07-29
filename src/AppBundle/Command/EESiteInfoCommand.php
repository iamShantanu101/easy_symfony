<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Debug\Debug;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class EESiteInfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ee-site:info')

            // the short description shown while running "php bin/console list"
            ->setDescription('Shows the site information from DB')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command shows the configuration...')
            // adding required argument
            ->addArgument('site-name', InputArgument::REQUIRED, 'Name of the site.')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('site-name');
        $webroot_path = getenv('WEBROOT_PATH');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $query = $em->createQuery(
            'SELECT e
            FROM AppBundle:EE e
            WHERE e.site_name = :site_name'
        )->setParameter('site_name', $name);

        $site = $query->getResult();
        print_r($site);

        $output->writeln([
        'Site Configuration Details',
        '==========================',
        $name,
        '',
        ]);
        $out = file_get_contents("$webroot_path/$name/$name.txt");
        print_r($out);
    }
}
