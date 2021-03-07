<?php

class WutModel extends Model {
    public function __construct() {
        $this->dbh = new PDO('mysql:host='. WUT_DB_HOST . ';dbname='. WUT_DB_NAME,WUT_DB_USER,WUT_DB_PASS);
    }
}