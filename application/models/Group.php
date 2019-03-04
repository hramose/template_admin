<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends Eloquent {

    public $table = 'groups';
    public $primaryKey = 'id';
    public $timestamps = false;

}