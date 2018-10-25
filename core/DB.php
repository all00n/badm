<?php

namespace core;
class DB {
    
    public $dbh;

    public function db() {
        include './setting/db_params.php';
        
        try {
            return new \PDO("mysql:host={$params['host']};dbname={$params['name']};charset=utf8;",$params['user'],$params['pass']);
        } catch (PDOException $ex) {
            return $ex->getMessage();
        }        
    }
}