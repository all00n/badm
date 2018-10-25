<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 22.10.2018
 * Time: 17:31
 */

include './models/UsersModel.php';

class UserController
{

    //класс для работы с бд
    private $model;

    public function __construct()
    {
        //создаём экземпляр класса можели
        $this->model = new UsersModel();
    }

    public function addUser(){
        //заголовок для ответа
        header('Content-Type: application/json; charset=utf-8');
        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);
        //валидируем данные
        if($this->validate($date)){
            echo json_encode(array(
                'code' => 400,
                'error'=>'invalid parameters'
            ));
            return;
        }
        //проверяем нет ли пользователя с таким мейлом
        if($this->model->checkUser($date['email'])){
            echo json_encode(array(
                'code' => 400,
                'error' => 'user with this email already exists'
            ));
            return;
        }
        //если всё в порядке создаём пользователя
        $user = $this->model->createUser(array(
            'name' => $date['name'],
            'email' => $date['email'],
            'password' => password_hash($date['password'],PASSWORD_DEFAULT),
            'phone' => $date['phone']
        ));
        if($user['status'] == TRUE){
            echo json_encode(array(
                'code' => 200,
                'date'=>'user created successfully'
            ));
        } else {
            echo json_encode(array(
                'code' => 400,
                'error' => $user['date']
            ));
        }
        return;
    }
//на входе получаем ид пользователя, возвращаем его данные если такой есть
    public function getUser($id){
        header('Content-Type: application/json; charset=utf-8');
        $user = $this->model->getUser($id);
        if($user){
            echo json_encode(array(
                'code' => 200,
                'user' => $user
            ));
        } else {
            echo json_encode(array(
                'code' => 400,
                'date' => 'User is not found'
            ));
        }
        return;
    }

    public function editUser($id){
        header('Content-Type: application/json; charset=utf-8');
        //name,email,phone,roles,access

        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);

        if($this->validate_for_edit($date)){
            echo json_encode(array(
                'code' => 400,
                'error'=>'invalid parameters'
            ));
            return;
        }

        $user = $this->model->editUser(array(
            'id'=> $id,
            'name' => $date['name'],
            'email' => $date['email'],
            'phone' => $date['phone'],
            'roles' => $date['roles'],
            'access' => $date['access']
        ));

        if($user['status'] == TRUE){
            echo json_encode(array(
                'code' => 200,
                'date'=>'user update successfully '
            ));
        } else {
            echo json_encode(array(
                'code' => 400,
                'error' => $user['date']
            ));
        }
        return;
    }

    // метод для вализации данных
    // возвращает bool значение
    private function validate($date){
        $errors = [];
        if($date['name'] == ''){
            $errors[] = 'not valid name';
        }

        if($date['email'] == ''){
            $errors[] = 'not valid email';
        }

        if($date['phone'] == ''){
            $errors[] = 'not valid password';
        }

        if($date['password'] == ''){
            $errors[] = 'not valid password';
        }

        if($date['password'] != $date['password_two']){
            $errors[] = 'not valid email';
        }
        return $errors;
    }

    private function validate_for_edit($date){
        $errors = [];
        if($date['name'] == ''){
            $errors[] = 'not valid name';
        }

        if($date['phone'] == ''){
            $errors[] = 'not valid phone';
        }

        if($date['email'] == ''){
            $errors[] = 'not valid email';
        }

        if($date['roles'] == ''){
            $errors[] = 'not valid roles';
        }

        if($date['access'] == ''){
            $errors[] = 'not valid access';
        }
        return $errors;
    }
}