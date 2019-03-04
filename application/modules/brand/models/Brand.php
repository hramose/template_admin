<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Brand extends Eloquent {

	public $table = 'm_brand';
	public $primaryKey = 'rowID';
	public $timestamps = false;

}