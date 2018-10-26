<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 26.10.2018
 * Time: 00:41
 */

include './models/TokenModel.php';

class TokenController
{
    private $token;
    private $model;
    private $lifetime;

    public function __construct($token)
    {
        $this->token = $token;
        $this->model = new TokenModel();
        $this->lifetime = include('./setting/token.php');
    }

    public function getPermission(){
        $permission = $this->model->getPermission($this->token);

        if($permission){
            $this->model->extension($this->token,$this->lifetime['lifetime']);
        }
        return $permission;
    }
}