<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Debug\Debug;

Debug::enable();

class EESiteShowCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ee-site:show')

            // the short description shown while running "php bin/console list"
            ->setDescription('Shows the site configuration')

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
        $out = file_get_contents("$webroot_path/$name/$name.txt");
        print_r($out);
    }
}
