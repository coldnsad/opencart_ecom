<?php

class ModelExtensionModuleStoreLocations extends Model {

    public function getCoordinates($location_id) {

        $query = $this->db->query("SELECT name, geocode FROM " . DB_PREFIX . "location WHERE location_id = '". $location_id ."'");

        return $query->row;       
    } 

}

?>