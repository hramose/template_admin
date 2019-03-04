<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Fetch the config variables from DB
| 
*/
class HookModel extends CI_Model {

    public function __construct()
    {
	    parent::__construct();
    }

    public function get_config()
    {
	    return $this->db->get('configurations');
    }

	public function get_company()
    {
	    return $this->db->get('company');
    }
	
    public function get_lang()
    {
        $query = $this->db->select('value')->where('config_key','language')->get('configurations');
        
		if ($query->num_rows() > 0){
            $row = $query->row();
            return $row->value;
        }
    }
    
}