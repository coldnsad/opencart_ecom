<?php
class ModelReportProductOfferState extends Model {
	public function getProducts() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p 
								  LEFT JOIN ". DB_PREFIX ."variants v 		      ON p.product_id = v.product_id
								  LEFT JOIN ". DB_PREFIX ."product_description pd ON p.product_id = pd.product_id
								  
								 WHERE (v.offer_id is NULL AND v.product_id is not NULL) OR v.product_id is NULL LIMIT 0");

		return $query->rows;
	}
	
	public function getValue($code) {
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "statistics WHERE `code` = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return null;	
		}
	}
	
	public function addValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "statistics SET `value` = (`value` + '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}
	
	public function editValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "statistics SET `value` = '" . (float)$value . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}
		
	public function removeValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "statistics SET `value` = (`value` - '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}	
}
