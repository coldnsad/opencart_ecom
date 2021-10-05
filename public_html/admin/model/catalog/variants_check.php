<?php

class ModelCatalogVariantsCheck extends Model {

    public function getMissedViewFromVariants() {

        $query = $this->db->query(
            "SELECT v.product_id, pd.name as product_name FROM ". DB_PREFIX ."variants v 
                    LEFT JOIN ". DB_PREFIX ."product_description pd ON pd.product_id = v.product_id WHERE v.view_id is NULL");

            return $query->rows;
    }

    public function searchViewByProductName($parsed_product_name) {

        $query = $this->db->query(
            "SELECT view_id, name FROM ". DB_PREFIX ."view_description WHERE name LIKE '". $parsed_product_name ."'");

            return $query->row;
    }

    public function addViewToVariants($product_data, $isNew = true) {

        if ($isNew) {
            $this->db->query(
                "INSERT INTO " . DB_PREFIX . "view SET  
                    quantity = 100, 
                    minimum = 1, 
                    subtract = 1, 
                    stock_status_id = 5, 
                    date_available = NOW(),
                    status = 1, 
                    noindex = 1, 
                    tax_class_id = 0, 
                    sort_order = 0, 
                    date_added = NOW(), date_modified = NOW()");

            $view_id = $this->db->getLastId();

            $this->db->query(
                "INSERT INTO " . DB_PREFIX . "view_description SET 
                    view_id = '" . $view_id . "', 
                    language_id = 1, 
                    name = '" . $product_data['product_name'] . "', 
                    description = '" . $product_data['product_name'] . "', 
                    meta_title = '" . $product_data['product_name'] . "'");
            
            $this->db->query("UPDATE ". DB_PREFIX ."variants SET view_id = '". $view_id ."' WHERE product_id = '". $product_data['product_id'] ."'");
        }
        else{
            $this->db->query("UPDATE ". DB_PREFIX ."variants SET view_id = '". $product_data['view_id'] ."' WHERE product_id = '". $product_data['product_id'] ."'");
        }
    }

}

?>