<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Fetch the config variables from DB
| 
*/
class AppModel extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }
    
	public function select_max_id($table,$where,$field)
	{
		$this->db->select_max($field);
		$query = $this->db->where($where)->get($table);
		
		if($query->num_rows()>0){
            foreach($query->result() as $q){
				return ((int)$q->$field);
			}
		}

	}	
    
	public function get_prefix($value)
	{
		$query = $this->db->where('module',$value)->get('sa_prefix');
		
		if($query->num_rows()>0){
            $q = $query->row();
		    return $q->prefix;
		}
	}	

	public function get_references($value)
	{
		$query = $this->db->where('type_ref',$value)->get('sa_reference');
		
		if($query->num_rows()>0){
            $q = $query->result();
		    return $q;
		}
	}

	public function get_references_by_type_no($type, $no)
	{
		$query = $this->db->where('type_ref',$type)->where('type_no',$no)->get('sa_reference');
		
		if($query->num_rows()>0){
            $q = $query->row();
		    return $q;
		}
	}	

}