<?php

use Cart\Length;

class ControllerCatalogVariantsCheck extends Controller {
    

    public function index(){

        $this->load->model('catalog/variants_check');

        $missed_view_data = $this->model_catalog_variants_check->getMissedViewFromVariants();
        $product_data = array();

        foreach($missed_view_data as $missed_view) {
            $parsed_product_name = explode(" ", $missed_view['product_name']);

            if (count($parsed_product_name) >= 3) {
                $str_for_search = "$parsed_product_name[0] $parsed_product_name[1] $parsed_product_name[2]";
            }else{
                $str_for_search = "$parsed_product_name[0]";
            }

            $view_data = $this->model_catalog_variants_check->searchViewByProductName($str_for_search);
            
            if($view_data) { // isNew = false / this is not new view
                $product_data = array(
                    'product_id'   => $missed_view['product_id'],
                    'product_name' => $missed_view['product_name'],
                    'view_id'      => $view_data['view_id']
                );
                $this->model_catalog_variants_check->addViewToVariants($product_data, false);
                print_r("Existed view added(product_id: " .  $product_data['product_id'] . ", product_name: " .  $product_data['product_name'] .", view_name: " . $view_data['name']);  		
                echo '<br>'; 
            }else{                    // isNew = true / this is new view
                $product_data = array(
                    'product_id'   => $missed_view['product_id'],
                    'product_name' => $missed_view['product_name']                    
                );
                $this->model_catalog_variants_check->addViewToVariants($product_data);
                print_r("New view added(product_id: " .  $product_data['product_id'] . ", product_name/view_name: " .  $product_data['product_name']);  		
                echo '<br>'; 
            }
        }      
    }
}

?>