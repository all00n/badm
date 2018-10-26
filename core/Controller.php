<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 25.10.2018
 * Time: 18:36
 */

abstract class Controller
{
    public function render($request){

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            $request
        );
        die();
    }
}