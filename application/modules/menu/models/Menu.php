<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Menu extends Eloquent {

	public $table = 'menu';
	public $primaryKey = 'menu_id';
	public $timestamps = false;

}