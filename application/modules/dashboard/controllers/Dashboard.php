<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {

	public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
    }

	public function index(){
		
		$this->load->blade('dashboard.views.dashboard.page');
	}
    
}

/* End of file Dashboard.php */
/* Location: ./application/modules/dashboard/controllers/Dashboard.php */