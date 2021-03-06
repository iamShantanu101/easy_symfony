<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Product;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Debug\Debug;

Debug::enable();

class EESiteListCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ee-site:list')

            // the short description shown while running "php bin/console list"
            ->setDescription('Lists all the sites in directory')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to list all the sites present in directory...')
        
        
        // adding options for creating site
        ->addOption(
        'enabled',
        null,
        InputOption::VALUE_NONE,
        'Lists only the sites which are enabled'
    
        )

        ->addOption(
        'disabled',
        null,
        InputOption::VALUE_NONE,
        'Lists only the sites which are disabled'
        )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input_option = $input->getOptions();

        foreach ($input_option as $key=>$value) {
            if ($value) {
                
                switch ($key) {
                    case ($key == "enabled"):
                        $this->listfiles($input, $output);
                      
                        // no break
                    case ($key == "disabled"):
                        $this->listfiles($input, $output);
                      
                        // no break
                    default:
                        $this->listfiles($input, $output);
                       

                }
            }
        }
    }

    protected function listfiles($input, $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $query = $em->createQuery(
            'SELECT e.site_name
             FROM AppBundle:EE e'
        );
        $site_names = $query->getResult();

        array_values($site_names);
        foreach ($site_names as $key ) {
                echo $key['site_name'] . "\n" ;
        }
    }
}
