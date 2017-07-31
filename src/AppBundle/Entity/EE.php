<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ee")
 */

class EE
{
	/**
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */	
    private $id;
    /**
     * @ORM\Column(name="site_name", type="string", unique=true, length=50, nullable=false)
     */
    private $site_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $site_type;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $cache_type;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $php_flag;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $mysql_flag;



    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getSite_name(){
        return $this->site_name;
    }

    public function setSite_name($site_name){
        $this->site_name = $site_name;
    }

    public function getSite_type(){
        return $this->site_type;
    }

    public function setSite_type($site_type){
        $this->site_type = $site_type;
    }

    public function getCache_type(){
        return $this->cache_type;
    }

    public function setCache_type($cache_type){
        $this->cache_type = $cache_type;
    }

    public function getPhp_flag(){
        return $this->php_flag;
    }

    public function setPhp_flag($php_flag){
        $this->php_flag = $php_flag;
    }

    public function getMysql_flag(){
        return $this->mysql_flag;
    }

    public function setMysql_flag($mysql_flag){
        $this->mysql_flag = $mysql_flag;
    }
}
