<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set config variables using DB
| 
*/
  //Loads configuration from database into global CI config
  function load_config(){
	$CI =& get_instance();

    $CI->load->library('ion_auth');
    if(!empty($CI->ion_auth->user()->row()->id)){
        $id = $CI->ion_auth->user()->row()->id;
    }else{
        $id = '';
    }

	foreach($CI->HookModel->get_config()->result() as $site_config)
	{
		$CI->config->set_item($site_config->config_key,$site_config->value);
	}

	foreach($CI->HookModel->get_company()->result() as $company)
	{
		$CI->config->set_item($company->company_config,$company->company_value);
	}
   
   	if($CI->config->item('timezone'))
	{
		date_default_timezone_set($CI->config->item('timezone'));
	}
	
  }
?>