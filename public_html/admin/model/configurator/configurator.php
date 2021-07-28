<?php

class ModelConfiguratorConfigurator extends Model {

    //Houses
    public function getHouses($data = array()) {  

        $sql = "SELECT * FROM conf_house";

        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

        $query = $this->db->query($sql);

        return $query->rows;    
    }
    
    public function getTotalHouses() {

        $query = $this->db->query("SELECT COUNT(house_id) AS total FROM conf_house");

        return $query->row['total'];
    }

    public function getHouse($house_id) {

        $query = $this->db->query("SELECT * FROM conf_house WHERE house_id = '" . $house_id . "'");

        return $query->row;    
    }
    
    public function addHouse($data) {

        //Add info about "configuration house", but without preview field(standart value)
        $this->db->query("INSERT INTO conf_house SET 
            name = '" . $this->db->escape($data['name']) . "', 
            image = '" . $this->db->escape($data['image']) . "', 
            status = '" . $this->db->escape($data['status']) . "', 
            preview = '" . $this->db->escape($data['preview']) . "'");

		$house_id = $this->db->getLastId();

        return $house_id;
    }

    public function editHouse($house_id, $data) {

        //Add info about "configuration house", but without preview field(standart value)
        $this->db->query("UPDATE conf_house SET 
            name = '" . $this->db->escape($data['name']) . "', 
            image = '" . $this->db->escape($data['image']) . "', 
            status = '" . $this->db->escape($data['status']) . "', 
            preview = '" . $this->db->escape($data['preview']) . "' 
            
            WHERE house_id = '". $house_id . "'");
    }

    public function deleteHouse($house_id) {

        $this->db->query("DELETE FROM conf_house WHERE house_id = '". $house_id . "'");

    } //End House    

    //Elements
    public function getElements($data = array()) {  

        $sql = "SELECT * FROM conf_elements";

        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

        $query = $this->db->query($sql);

        return $query->rows;    
    }

    public function getTotalElements() {

        $query = $this->db->query("SELECT COUNT(element_id) AS total FROM conf_elements");

        return $query->row['total'];
    }

    public function getElement($element_id) {

        $query = $this->db->query("SELECT * FROM conf_elements WHERE element_id = '" . $element_id . "'");

        return $query->row;    
    } 
    
    public function addElement($data) {

        $this->db->query("INSERT INTO conf_elements SET 
            name = '" . $this->db->escape($data['name']) . "', 
            sort = '" . $this->db->escape($data['sort']) . "'");

		$element_id = $this->db->getLastId();

        return $element_id;
    }

    public function editElement($element_id, $data) {

        $this->db->query("UPDATE conf_elements SET 
            name = '" . $this->db->escape($data['name']) . "', 
            sort = '" . $this->db->escape($data['sort']) . "' 
            
            WHERE element_id = '". $element_id . "'");
    }

    public function deleteElement($element_id) {

        $this->db->query("DELETE FROM conf_elements WHERE element_id = '". $element_id . "'");
    
    }//End Elements

    //Material
    public function getMaterials($data = array()) {  

        $sql = "SELECT m.material_id, m.name, m.element_id, m.status, e.name AS element_name FROM conf_materials m LEFT JOIN conf_elements e ON (m.element_id = e.element_id)";


        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

        $query = $this->db->query($sql);

        return $query->rows;    
    }

    public function getTotalMaterials() {

        $query = $this->db->query("SELECT COUNT(material_id) AS total FROM conf_materials");

        return $query->row['total'];
    }

    public function getMaterial($material_id) {

        $query = $this->db->query("SELECT m.material_id, m.name, m.element_id, m.status, e.name AS element_name FROM conf_materials m 
        
        LEFT JOIN conf_elements e ON (m.element_id = e.element_id)
        
        WHERE material_id = '". $material_id ."'");

        return $query->row;    
    } 
    
    public function addMaterial($data) {

        $this->db->query("INSERT INTO conf_materials SET 
            name        = '" . $this->db->escape($data['name']) . "', 
            element_id  = '" . $data['element_id'] . "', 
            status      = '" . $this->db->escape($data['status']) . "'");

		$element_id = $this->db->getLastId();

        return $element_id;
    }

    public function editMaterial($material_id, $data) {

        $this->db->query("UPDATE conf_materials SET 
            name       = '" . $this->db->escape($data['name']) . "', 
            element_id = '" . $data['element_id'] . "', 
            status     = '" . $data['status'] . "' 
            
            WHERE material_id = '". $material_id . "'");
    }

    public function deleteMaterial($material_id) {

        $this->db->query("DELETE FROM conf_materials WHERE material_id = '". $material_id . "'");
    
    }//End Material

     //Series
     public function getSeries($data = array()) {  

        $sql = "SELECT s.series_id, s.name, s.material_id, s.sort, s.status, m.name AS material_name FROM conf_series s LEFT JOIN conf_materials m ON (m.material_id = s.material_id)";


        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

        $query = $this->db->query($sql);

        return $query->rows;    
    }

    public function getTotalSeries() {

        $query = $this->db->query("SELECT COUNT(series_id) AS total FROM conf_series");

        return $query->row['total'];
    }

    public function getSerie($serie_id) {

        $query = $this->db->query("SELECT s.series_id, s.name, s.material_id, s.sort, s.status, m.name AS material_name FROM conf_series s
        
        LEFT JOIN conf_materials m ON (m.material_id = s.material_id)
        
        WHERE series_id = '". $serie_id ."'");

        return $query->row;    
    } 
    
    public function addSerie($data) {

        $this->db->query("INSERT INTO conf_series SET 
            name        = '" . $this->db->escape($data['name']) . "', 
            material_id = '" . $data['material_id'] . "', 
            sort        = '" . $data['sort'] . "', 
            status      = '" . $this->db->escape($data['status']) . "'");

		$series_id = $this->db->getLastId();

        return $series_id;
    }

    public function editSerie($series_id, $data) {

        $this->db->query("UPDATE conf_series SET 
            name       = '" . $this->db->escape($data['name']) . "', 
            material_id= '" . $data['material_id'] . "',
            sort       = '" . $data['sort'] . "',  
            status     = '" . $data['status'] . "' 
            
            WHERE series_id = '". $series_id . "'");
    }

    public function deleteSerie($series_id) {

        $this->db->query("DELETE FROM conf_series WHERE series_id = '". $series_id . "'");
    
    }//End Series

    //Product(views)
    public function getProducts($data = array()) {  

        $sql = "SELECT v.view_id, v.name, v.series_id, v.material_id, v.status, v.product_id, s.name AS serie_name, m.name AS material_name, pd.name AS product_name
        
            FROM conf_views v 
            LEFT JOIN conf_series s                           ON (v.series_id = s.series_id)
            LEFT JOIN conf_materials m                        ON (v.material_id = m.material_id)
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (v.product_id = pd.product_id)";


        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

        $query = $this->db->query($sql);

        return $query->rows;    
    }

    public function getTotalProducts() {

        $query = $this->db->query("SELECT COUNT(view_id) AS total FROM conf_views");

        return $query->row['total'];
    }

    public function getProduct($product_id) {

        $query = $this->db->query("SELECT v.view_id, v.name, v.series_id, v.material_id, v.status, v.product_id, s.name AS serie_name, m.name AS material_name, pd.name AS product_name
        
            FROM conf_views v 
            LEFT JOIN conf_series s                           ON (v.series_id = s.series_id)
            LEFT JOIN conf_materials m                        ON (v.material_id = m.material_id)
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (v.product_id = pd.product_id)
        
        WHERE view_id = '". $product_id ."'");

        return $query->row;    
    } 
    
    public function addProduct($data) {

        $this->db->query("INSERT INTO conf_views SET           
            name                = '" . $this->db->escape($data['name']) . "', 
            series_id           = '" . $data['series_id'] . "',  
            material_id         = '" . $data['material_id'] . "',  
            status              = '" . $this->db->escape($data['status']) . "',
            product_id          = '" . $data['product_id'] . "'");

		$view_id = $this->db->getLastId();

        return $view_id;
    }

    public function editProduct($view_id, $data) {

        $this->db->query("UPDATE conf_views SET 
            name                = '" . $this->db->escape($data['name']) . "', 
            series_id           = '" . $data['series_id'] . "',  
            material_id         = '" . $data['material_id'] . "',  
            status              = '" . $this->db->escape($data['status']) . "',
            product_id          = '" . $data['product_id'] . "' 
            
            WHERE view_id = '". $view_id . "'");
    }

    public function deleteProduct($view_id) {

        $this->db->query("DELETE FROM conf_views WHERE view_id = '". $view_id . "'");
    
    }

    public function addItem($data) {

        $this->db->query("INSERT INTO conf_item SET 
            image               = '" . $data['item_image'] . "', 
            name                = '" . $this->db->escape($data['item_name']) . "', 
            prewiev             = '" . $data['item_preview'] . "', 
            house_id            = '" . $data['house_id'] . "',  
            status              = '" . $this->db->escape($data['item_status']) . "',
            view_id             = '" . $data['view_id'] . "',  
            manufacturer_id     = '" . $data['manufacturer_id'] . "'");

        $item_id = $this->db->getLastId();
        
        return $item_id;
    }

    //Data from DB_PREFIX_product_description(ajax)
    public function getProductsForLink($data = array()) {
        
        $sql = "SELECT * FROM " . DB_PREFIX . "product_description";

        if (!empty($data['filter_name'])) {
            $sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'name',
            'sort_order'
        );

        /* if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        } */

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    
    }

    //Data from conf_views(ajax)
    public function getViewsForLink($data = array()) {
        
        $sql = "SELECT * FROM conf_views";

        if (!empty($data['filter_name'])) {
            $sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sort_data = array(
            'name',
            'sort_order'
        );

        /* if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        } */

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    
    }//End Product(views)

}

?>