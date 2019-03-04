<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class UserMenu extends Eloquent {

	public $table = 'users_menu';
	public $primaryKey = 'id_user_menu';
	public $timestamps = false;

}