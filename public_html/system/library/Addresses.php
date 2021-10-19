<?php

class Addresses {

    private $addresses;
    private $file;
    private $config;
	private $db;

    public function __construct($registry, $file){
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->file = $file;
    }       

    public function addAddress($key, $value){
        $this->addresses[$key] = $value;  
    }
    
    public function getDataFromFile(){
        $this->addresses = json_decode(file_get_contents($this->file),true);
    }

    public function saveNewDataInFile(){
        file_put_contents($this->file, json_encode($this->addresses));
    }
    
}

?>