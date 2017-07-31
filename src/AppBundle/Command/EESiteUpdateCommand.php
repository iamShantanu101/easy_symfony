<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Debug\Debug;
use AppBundle\Entity\EE;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class EESiteUpdateCommand extends ContainerAwareCommand
{
    private $name;
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ee-site:update')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new site.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a site...')
        
            // adding required arguments
            ->addArgument('site-name', InputArgument::REQUIRED, 'Name of the site you want to create')
        
        // adding options for updating site

        ->addOption(
        'php7',
        null,
        InputOption::VALUE_NONE,
        'Simple php site with no database'
        )

        ->addOption(
        'mysql',
        null,
        InputOption::VALUE_NONE,
        'Simple php site with database'
        )

        ->addOption(
        'wp',
        null,
        InputOption::VALUE_NONE,
        'Update a site to WordPress'
        )

        ->addOption(
        'php',
        null,
        InputOption::VALUE_NONE,
        'Update a site to PHP'
        )

        ->addOption(
        'wpredis',
        null,
        InputOption::VALUE_NONE,
        'Update a site to WordPress with Redis cache'

        )
        ->addOption(
        'wpfc',
        null,
        InputOption::VALUE_NONE,
        'Update a site to WordPress with FastCGI cache'
    
    );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site_type = null;
        $cache_type = null;
        $PHP_flag = null;
        $Mysql_flag = null;

        $this->name = $input->getArgument('site-name');
        $input_option = $input->getOptions();
        $webroot_path = getenv('WEBROOT_PATH');
        // create a directory hierarchy wrt site_name
        $structure = "$webroot_path/$this->name";

        $my_file = "$structure/$this->name.txt";
        $handler = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file); //implicitly creates file
        $filename = "$structure/$this->name.txt";
        
        $output->writeln([
        'Site Updation',
        '============',
        $this->name,
        '',
        ]);
                
        $previousValue = null;

        foreach ($input_option as $key=>$value) {
            if ($value) {
                echo "$key\n";
                switch ($key) {
                    case ($key == "env"):
                        break;

                    case ($key == "php"):
                        echo "Inside php case";
                        if ($previousValue == "wp") {
                            break;
                        }

                        $dirname = "$webroot_path/$this->name";
                        fclose($handler);
                        array_map('unlink', glob("$dirname/*.txt"));
                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'php';
                        $cache_type = 'disabled';
                        $PHP_flag = '5.6';
                        $Mysql_flag = 'no';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                    case ($key == "php7"):
                        echo "Inside php7 case";

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'php';
                        $cache_type = 'disabled';
                        $PHP_flag = '7.0';
                        $Mysql_flag = 'no';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        if ($previousValue) {
                            echo "$previousValue";
                        }
                        $previousValue = $key;
                        break;

                    case ($key == "mysql"):

                        if ($input->getOption('php7')) {
                            $PHP_flag = '7.0';
                        } else {
                            $PHP_flag = '5.6';
                        }

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'php + mysql';
                        $cache_type = 'disabled';
                        $Mysql_flag = '5.6';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                case ($key == "wp"):
                        echo "Inside mysql case";

                        if ($previousValue) {
                            echo "This is previous $previousValue and this is key $key";
                        }
                        if ($previousValue == "php7") {
                            $PHP_flag = '7.0';
                        } else {
                            $PHP_flag = '5.6';
                        }
                        $previousValue = $key;

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'WordPress';
                        $cache_type = 'disabled';
                        $Mysql_flag = '5.6';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                        

                case ($key == "wpredis"):
                        echo "Inside mysql case";

                        if ($previousValue == "php7") {
                            $PHP_flag = '7.0';
                        } else {
                            $PHP_flag = '5.6';
                        }

                        if ($previousValue) {
                            $previousValue = $key;
                        }
                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";
  
                        $site_type = 'WordPress';
                        $cache_type = 'Redis';
                        $Mysql_flag = '5.6';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                case ($key == "wpfc"):
                        echo "Inside mysql case";

                        if ($previousValue == "php7") {
                            $PHP_flag = '7.0';
                        } else {
                            $PHP_flag = '5.6';
                        }
                        if ($previousValue) {
                            $previousValue = $key;
                        }

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'WordPress';
                        $cache_type = 'FastCGI';
                        $Mysql_flag = '5.6';
                        $this->updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                }
            }
        }
    }

    private function updatedb($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag)
    {
        $name = $input->getArgument('site-name');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $query = $em->createQuery(
            'UPDATE AppBundle:EE e
            SET   e.site_type  = :site_type,
                  e.cache_type = :cache_type,
                  e.php_flag   = :PHP_flag,
                  e.mysql_flag = :Mysql_flag
            WHERE e.site_name  = :site_name'
        )->setParameter('site_name',  $name)
         ->setParameter('site_type',  $site_type)
         ->setParameter('cache_type', $cache_type)
         ->setParameter('PHP_flag',   $PHP_flag)
         ->setParameter('Mysql_flag', $Mysql_flag);

         $update_query = $query->getResult();

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();


    }

    private function writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag)
    {
        $webroot_path = getenv('WEBROOT_PATH');
        $this->name = $input->getArgument('site-name');
        $input_option = $input->getOptions();
 
        $structure = "$webroot_path/$this->name";
        $my_file = "$structure/$this->name.txt";
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
        $filename = "$structure/$this->name.txt";

        $filecontent = file_get_contents($filename);
        $filecontent .= "site-type = $site_type
cache-type = $cache_type
PHP = $PHP_flag
Mysql = $Mysql_flag
";
        $output->writeln([
            "site-type = $site_type",
            "cache-type = $cache_type",
            "PHP = $PHP_flag",
            "Mysql = $Mysql_flag",
            "",
        ]);
        file_put_contents($filename, $filecontent);
    }
}
