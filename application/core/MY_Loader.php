<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

use \Philo\Blade\Blade;

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	public function blade($html,$data = array())
	{
		$views = APPPATH . 'modules';
		$cache = APPPATH . '/cache/blade';
		$file = $views . '/' . $html;
		$blade = new Blade($views, $cache);
		echo $blade->view()->make($html,$data)->render();
	}
	
}