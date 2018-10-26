<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 23.10.2018
 * Time: 20:50
 */
class FibonacciController extends Controller
{
    public function fibonacci($n){
        $result =  round(pow((sqrt(5)+1)/2, $n) / sqrt(5));
        $this->render(array('result'=>$result));

    }
}