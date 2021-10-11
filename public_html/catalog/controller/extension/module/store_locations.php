<?php

class ControllerExtensionModuleStoreLocations extends Controller {

    public function index($settings) {

		//$this->load->model('extension/module/store_locations');
		$this->load->model('localisation/location');
        $this->load->language('extension/module/store_locations');

		$locations_id = $this->config->get('config_location');		
		$data['width'] = $settings['width'];
		$data['height'] = $settings['height'];
		foreach ($locations_id as $location_id) {

			 //$result = $this->model_extension_module_store_locations->getCoordinates($location_id);
			 $result = $this->model_localisation_location->getLocation($location_id);
			 //$decoded = json_decode($result['geocode'], true);
			 $decoded = explode(",", $result['geocode']);
			 $data['stores_coordinates'][] = array(				
				'type' => 'Feature',
				'properties' => array(
					'balloonContent' => $result['name']
				),
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array($decoded[0], $decoded[1])
				)
			 );
		}

		return $this->load->view('extension/module/store_locations', $data);
    }
}

?>