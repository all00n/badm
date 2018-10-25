<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 22.10.2018
 * Time: 21:56
 */

include './models/LoginModel.php';

class LoginController
{
    private $email = '';
    private $password = '';
    private $lifetime;
    //класс для работы с бд
    private $model;

    public function __construct()
    {
        include './setting/token.php';

        $this->lifetime = include('./setting/token.php');

        //промежутачная переменная для записи данных
        $date = json_decode(file_get_contents('php://input'), true);

        $this->email = trim($date['email']);
        $this->password = $date['password'];

        $this->model = new LoginModel();
    }
//метод авторизации возвращает токен или ошибку с описанием ошибки
    public function login(){
        header('Content-Type: application/json; charset=utf-8');
        if($this->validate()){
            echo json_encode(array(
                'code' => 422,
                'error'=>'invalid data'
            ));
            return;
        }

        $user_info = $this->model->getPassword($this->email);

        if(password_verify($this->password, $user_info['password'])){

            $token = $this->model->remember($user_info['id'],$this->lifetime['lifetime']);

            echo json_encode(array(
                'code' => 200,
                'token' => $token
            ));
            return;
        } else {
            echo json_encode(array(
                'code' => 401,
                'error'=>'wrong login or password'
            ));
            return;
        }
    }

    private function validate(){
        $errors = [];

        if($this->email == ''){
            $errors[] = 'not valid email';
        }

        if($this->password == ''){
            $errors[] = 'not valid password';
        }

        return $errors;
    }
}