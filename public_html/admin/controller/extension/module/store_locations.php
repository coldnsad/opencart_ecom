<?php

class ControllerExtensionModuleStoreLocations extends Controller {

    public function index() {

		$this->load->model('extension/module/store_locations');
        
        $url = '';
        
        $this->load->language('extension/module/store_locations');

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/store_locations', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$locations_id = $this->config->get('config_location');		

		foreach ($locations_id as $location_id) {

			 $result = $this->model_extension_module_store_locations->getCoordinates($location_id);
			 $decoded = json_decode($result['geocode'], true);
			 $data['stores_coordinates'][] = array(				
				'type' => 'Feature',
				'properties' => array(
					'balloonContent' => $result['name']
				),
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array($decoded['lattitude'], $decoded['longitude'])
				)
			 );
		}


        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/store_locations', $data));
    }
}

?>