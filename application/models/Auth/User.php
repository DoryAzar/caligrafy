<?php

use Caligrafy\Model;

class User extends Model {
    public $id;
    public $username;
    public $passcode;
    public $permissions;
    public $created_at;
    public $modified_at;
    
}