<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Debug\Debug;

Debug::enable();

class EESiteDeleteCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ee-site:delete')

            // the short description shown while running "php bin/console list"
            ->setDescription('Deletes a site')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to delete a site...')
        
            // adding required arguments
            ->addArgument('site-name', InputArgument::REQUIRED, 'Name of the site you want to delete')
        
        // adding options for creating site
        ->addOption(
        'files',
        null,
        InputOption::VALUE_NONE,
        'Delete website webroot only'
    
        )

        ->addOption(
        'db',
        null,
        InputOption::VALUE_NONE,
        'Delete website DB only'
        )

        ->addOption(
        'no-prompt',
        null,
        InputOption::VALUE_NONE,
        'Delete website without prompt'
        )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('site-name');
        $input_option = $input->getOptions();

        // get website directory from env variable
        $webroot_path = getenv('WEBROOT_PATH');
        // webroot dir
        $dirname = "$webroot_path/$name";

        foreach ($input_option as $key=>$value) {
            if ($value) {
                switch ($key) {
                    case ($key == "env"):
                            break;
                    case ($key == "no-prompt"):
                        array_map('unlink', glob("$dirname/*.*"));
                        rmdir($dirname) or die('Cannot delete site:  '.$name);
                        $output->writeln([
                            'Deleted website successfully!',
                           '',
                           ]);
                    break;
                    case ($key == "files"):
                        $helper = $this->getHelper('question');
                        $question = new ConfirmationQuestion('This will delete Website Webroot only, Continue?', false);
                        if (!$helper->ask($input, $output, $question)) {
                            return;
                        }
                        array_map('unlink', glob("$dirname/*.*"));
                        rmdir($dirname) or die('Cannot delete site website webroot:  '.$name);
                        $output->writeln([
                            'Deleted website webroot successfully!',
                            '',
                        ]);
                     break;
                     case ($key == "db"):
                        $question = new ConfirmationQuestion('This will delete Website DB only, Continue?', false);
                        if (!$helper->ask($input, $output, $question)) {
                            return;
                        }

                        $output->writeln([
                        'Deleted website DB successfully',
                                        '',
                        ]);
                     break;
                }
            } else {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('This will delete website as well as DB, continue?', false);
                if (!$helper->ask($input, $output, $question)) {
                    return;
                }
                array_map('unlink', glob("$dirname/*.*"));
                rmdir($dirname) or die('Cannot delete site website webroot:  '.$name);
                $output->writeln([
                       'Deleted webroot and DB successfully',
                       '',
                       ]);
                return;
            }
        }
    }
}
