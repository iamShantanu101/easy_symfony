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

        $this->name = $input->getArgument('site-name');
        $input_option = $input->getOptions();
        $webroot_path = getenv('WEBROOT_PATH');
        // create a directory hierarchy wrt site_name
        $structure = "$webroot_path/$this->name";
        //if (!mkdir($structure, 0777, true)) {
        //    die('Failed to create folders...');
        //}
        $my_file = "$structure/$this->name.txt";
        $handler = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
        $filename = "$structure/$this->name.txt";
        
        $output->writeln([
        'Site Updation',
        '============',
        $this->name,
        '',
        ]);
        
        var_dump($input_option);
        
        $previousValue = null;

        foreach ($input_option as $key=>$value) {
            if ($value) {
                echo "$key\n";
                switch ($key) {
                    case ($key == "env"):
                        break;

                    case ($key == "php"):
                        //if ($previousValue == "wp") {
                        //    break;
                        //}
                        $this->writefile($input, $output, $key);
                        //$site_type = 'php';
                        //    $cache_type = 'disabled';
                        //$PHP_flag = '5.6';
                        //$Mysql_flag = 'no';
                        //$this->writefile($input, $output, $site_type, $cache_type, $PHP_flag, $Mysql_flag);
                        //if ($previousValue) {
                        //    echo $previousValue;
                        //}
                        //$previousValue = $key;
                        break;

                    case ($key == "php7"):
                
                        //$site_type = 'php';
                
                        //$cache_type = 'disabled';
                        //$PHP_flag = '7.0';
                        //$Mysql_flag = 'no';
                        //echo "Inside php7";
                        $this->writefile($input, $output, $key);
                        //if ($previousValue) {
                        //    echo "$previousValue";
                        //}
                        //$previousValue = $key;
                        break;

                    case ($key == "mysql"):

                        //$site_type = 'php + mysql';
                        //$cache_type = 'disabled';
                        //$Mysql_flag = 'yes';
                        //if ($input->getOption('php7')) {
                        //    $PHP_flag = '7.0';
                        //} else {
                        //    $PHP_flag = '5.6';
                        //}
                        $this->writefile($input, $output, $key);
                        break;

                case ($key == "wp"):

                        //echo "Inside wp with previousvalue $previousValue";
                        //$site_type = 'Wordpress';
                        //    $cache_type = 'disabled';
                        //$Mysql_flag = '5.6';
                        //if ($previousValue) {
                        //    echo "This is previous $previousValue and this is key $key";
                        //}
                        //if ($previousValue == "php7") {
                        //    $PHP_flag = '7.0';
                        //} else {
                        //    $PHP_flag = '5.6';
                        //}
                        //$previousValue = $key;
                        $this->writefile($input, $output, $key);
                        break;

                case ($key == "wpredis"):
                        //$site_type = 'Wordpress';
                        //    $cache_type = 'Redis';
                        //$Mysql_flag = 'yes';
                        //if ($previousValue == "php7") {
                        //    $PHP_flag = '7.0';
                        //} else {
                        //    $PHP_flag = '5.6';
                        //}
                        //if ($previousValue) {
                        //    $previousValue = $key;
                        //}

                        $this->writefile($input, $output, $key);
                        break;

                case ($key == "wpfc"):
                        //$site_type = 'Wordpress';
                        //    $cache_type = 'FastCGI';
                        //    $Mysql_flag = 'yes';
                        //    if ($previousValue == "php7") {
                        //        $PHP_flag = '7.0';
                        //    } else {
                        //     $PHP_flag = '5.6';
                        // }
                        // if ($previousValue) {
                        //     $previousValue = $key;
                        // }
                        $this->writefile($input, $output, $key);
                        break;
                }
            }
        }
    }
    private function writefile($input, $output, $key)
    {
        echo "Inside writefile with $key";
        $string1 =  substr($key, 0, -3);
        echo "$string1";
        $webroot_path = getenv('WEBROOT_PATH');
        $this->name = $input->getArgument('site-name');
        $input_option = $input->getOptions();
 
        $structure = "$webroot_path/$this->name";
        //if (!mkdir($structure, 0777, true)) {
        //    die('Failed to create folders...');
        //}
        //$my_file = $structure . "/" . $this->name . ".txt";
        $my_file = "$webroot_path/$this->name/$this->name.txt";
        //$handler = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
        
        /*echo "Key above php $key";
        if ($key == "php") {
            if ($handler) {
                while(($line = fgets($handler)) !== false) {
                    if (strpos($line, 'html') != false) {
                        str_replace('site-type = html', 'site-type = php', $line);
                        str_replace('PHP = no', 'PHP = 5.6', $line);    
                    } else {
                        echo "Cannot update site with --php";
                    }                
                }            
                fclose($this->handler);
            } else {
                echo "Error opening file";
            }
        }
        echo "This is key before php7 $key";
        echo "This is handler before php7 $handler";*/
        if ($key == "php7") {
            $file = fopen("/Users/shantanudeshpande/symfony_playground/ee/example.com/example.com.txt", "r") or exit("Unable to open file!");
            //Output a line of the file until the end is reached
            //while(!feof($file))
            //{
            //    echo "File read line by line";
            //    $line = fgets($file);
            //    print_r(ora_books);
            //}
            $pattern = "/\bhtml\b/i"; 
            $ora_books = preg_grep($pattern, file($my_file));
            print_r($ora_books);    
            fclose($file);
            /*echo "key=php7 succeeeded";
            echo "Myfile in php7 $my_file";
            $lines = file($my_file);
            foreach ($lines as $line) {
                echo 'This is line! $line';
                if (strpos($line, 'html') != false) {
                        str_replace('site-type = html', 'site-type = php', $line);
                        str_replace('PHP = no', 'PHP = 5.6', $line);  
                }    
            }*/

            /*if ($handler) {
                echo "Inside php7 handler";
                while(($buffer = fgets($my_file, 4096)) !== false) {
                    echo "$buffer";        
                    // updating html site
                    if (!feof($handle)) {
                        echo "Error: unexpected fgets() fail\n";
                    }
                    if (strpos($line, 'html') != false) {
                        str_replace('site-type = html', 'site-type = php', $line);
                        str_replace('PHP = no', 'PHP = 5.6', $line);  
                    }
                    // updating php 5.6 site to php7.0
                    if (strpos($line, 'site-type = php') != false && strpos($line, 'PHP = 5.6') != false) {
                        str_replace('PHP = 5.6', 'PHP = 7.0', $line);
                    }    
                    // updating wp site to php7.0
                    if (strpos($line, 'site-type = Wordpress') != false) {
                        str_replace('PHP = 5.6', 'PHP = 7.0', $line);
                    }    
                    // updating wpredis site to php7.0
                    if (strpos($line, 'cache-type = Redis') != false) {
                        str_replace('PHP = 5.6', 'PHP = 7.0', $line);
                    }
                    // updating wpfc site to php7.0
                    //if (strpos($line, ''))    

                }
                fclose($handler);
            }
            else {
                echo "Error opening file";
            }*/
        }
        echo "Key above wp $key";    
        if ($key == "wp") {
            echo "inside wpenv";
            if ($handler) {
                echo "Inside handler $handler";
                //while(! feof($handler)) {
                    echo "Inside wp while $key";
                    //$my_text = substr_count($my_file,"html");   
                    //updating html to wp site
                    //if (strpos(fgets($handler), 'html') != false) {
                    //    echo "Inside wp and html";
                    //    str_replace('site-type = html', 'site-type = Wordpress', $line);
                    //}
                    //echo "My text $my_text";
                    /*if ($my_text) {
                        echo "Inside wp and html";
                        str_replace('site-type = html', 'site-type = Wordpress', $line);
                    }*/
                    echo "$my_file";
                    $searchstring = 'html';
                    if(file_exists($my_file)){//if myFile exists check it for searchString:
                        if(exec('grep '.escapeshellarg($searchstring).' '.$my_file)) {
                            echo "***ALERT! $searchstring already exists in $my_file!!!<br>";
                        }
                    }            
                    //updating php site to wp site
                    if (strpos(fgets($handler), 'php') != false) {
                        str_replace('site-type = php', 'site-type = Wordpress', $line);

                    $add_mysql = "Mysql = 5.6";
                    $myfile = file_put_contents('$this->filename', $add_mysql.PHP_EOL , FILE_APPEND | LOCK_EX);
                    }

                 
                fclose($handler);
            } else {
                echo "Error opening file";
            }    
        }

        if ($key == "wpredis") {
            if ($handler) {
                while(($line = fgets($handler)) != false) {

                   // updating html site to wpredis
                   if(strpos($line, 'html') != false) {
                        str_replace('site-type = html', 'site-type = Wordpress', $line);
                        str_replace('cache-type = disabled', 'cache-type = Redis', $line);
                        str_replace('PHP = no', 'PHP = 5.6', $line);
                        str_replace('Mysql = no', 'Mysql = 5.6', $line);
                   } 


                }
                fclose($handler);
            } else {
                echo "Error opening file";
            }
        }    


        /*$structure = "$webroot_path/$this->name";
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
        file_put_contents($filename, $filecontent);*/
    }
}
