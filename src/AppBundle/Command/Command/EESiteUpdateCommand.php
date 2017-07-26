<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Debug\Debug;

Debug::enable();

class EESiteUpdateCommand extends Command
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
        'Create a single Wordpress site'
        )

        ->addOption(
        'php',
        null,
        InputOption::VALUE_NONE,
        'Create a simple php 5.6 site'
        )

        ->addOption(
        'wpredis',
        null,
        InputOption::VALUE_NONE,
        'Create a Wordpress site with redis cache'

        )
        ->addOption(
        'wpfc',
        null,
        InputOption::VALUE_NONE,
        'Create a Wordpress site with FastCGI cache'
    
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
        //if (!mkdir($structure, 0777, true)) {
        //    die('Failed to create folders...');
        //}
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
                        $dirname = "$webroot_path/$this->name";
                        fclose($handler);
                        array_map('unlink', glob("$dirname/*.txt"));
                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $site_type = 'php';
                        $cache_type = 'disabled';
                        $PHP_flag = '5.6';
                        $Mysql_flag = 'no';
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
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                    case ($key == "mysql"):
                        echo "Inside mysql case";

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";
                        $string = "PHP = 5.6";

                        $count = substr_count(file_get_contents($my_file), $string);

                        if ($count >= 1) {
                            $PHP_flag = '5.6';
                        } else {
                            $PHP_flag = '7.0';
                        }

                        $site_type = 'php';
                        $cache_type = 'disabled';
                        $Mysql_flag = '5.6';
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                case ($key == "wp"):
                        echo "Inside mysql case";

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $string = "PHP = 5.6";

                        $count = substr_count(file_get_contents($my_file), $string);
                        if ($count >= 1) {
                            $PHP_flag = '5.6';
                        } else {
                            $PHP_flag = '7.0';
                        }

                        $site_type = 'WordPress';
                        $cache_type = 'disabled';
                        $Mysql_flag = '5.6';
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                        

                case ($key == "wpredis"):
                        echo "Inside mysql case";

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $string = "PHP = 5.6";

                        $count = substr_count(file_get_contents($my_file), $string);
                        if ($count >= 1) {
                            $PHP_flag = '5.6';
                        } else {
                            $PHP_flag = '7.0';
                        }

                        $site_type = 'WordPress';
                        $cache_type = 'Redis';
                        $Mysql_flag = '5.6';
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                case ($key == "wpfc"):
                        echo "Inside mysql case";

                        fclose($handler);
                        unlink($filename);

                        $handler = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);
                        $filename = "$structure/$this->name.txt";

                        $string = "PHP = 5.6";

                        $count = substr_count(file_get_contents($my_file), $string);
                        if ($count >= 1) {
                            $PHP_flag = '5.6';
                        } else {
                            $PHP_flag = '7.0';
                        }

                        $site_type = 'WordPress';
                        $cache_type = 'FastCGI';
                        $Mysql_flag = '5.6';
                        $this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        break;

                }
            }
        }
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
