<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$this->load->blade('default.views.layouts.default');
	}

}

/* End of file Page.php */
/* Location: ./application/modules/starter/controllers/Page.php */