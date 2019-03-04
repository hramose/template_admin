<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Area extends Eloquent {

	public $table = 'm_area';
	public $primaryKey = 'rowID';
	public $timestamps = false;

}