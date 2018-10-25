<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 23.10.2018
 * Time: 20:50
 */
class FibonacciController {
    public function fibonacci($n){
        header('Content-Type: application/json; charset=utf-8');
        $result =  round(pow((sqrt(5)+1)/2, $n) / sqrt(5));
        echo json_encode(array('result'=>$result));
        return;
    }
}