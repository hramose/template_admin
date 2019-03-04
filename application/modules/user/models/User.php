<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {

	public $table = 'users';
	public $primaryKey = 'id';
	public $timestamps = false;

}