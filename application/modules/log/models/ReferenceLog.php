<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class ReferenceLog extends Eloquent {

	public $table = 'reference_logs';
	public $primaryKey = 'rowID';
	public $timestamps = false;

}