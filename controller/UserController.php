<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 22.10.2018
 * Time: 17:31
 */

include './models/UsersModel.php';

class UserController extends Controller
{

    //класс для работы с бд
    private $model;

    public function __construct()
    {
        //создаём экземпляр класса можели
        $this->model = new UsersModel();
    }

    public function addUser(){
        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);
        //валидируем данные
        if($bad = $this->validate($date)){
            $this->render(array(
                'code' => 400,
                'error' => 'invalid parameters',
                'description'=> $bad
            ));
        }
        //проверяем нет ли пользователя с таким мейлом
        if($this->model->checkUser($date['email'])){
            $this->render(array(
                'code' => 400,
                'error' => 'user with this email already exists'
            ));
        }
        //если всё в порядке создаём пользователя
        $user = $this->model->createUser(array(
            'name' => $date['name'],
            'email' => $date['email'],
            'password' => password_hash($date['password'],PASSWORD_DEFAULT),
            'phone' => $date['phone'],
            'roles' => $date['roles']
        ));
        if($user['status'] == TRUE){
            $this->render(array(
                'code' => 200,
                'user_id' => $user['date']
            ));
        } else {
            $this->render(array(
                'code' => 400,
                'error' => $user['date']
            ));
        }
    }
//на входе получаем ид пользователя, возвращаем его данные если такой есть
    public function getUser($id){
        header('Content-Type: application/json; charset=utf-8');
        $user = $this->model->getUser($id);
        if($user){
            $this->render(array(
                'code' => 200,
                'user' => $user
            ));
        } else {
            $this->render(array(
                'code' => 400,
                'date' => 'User is not found'
            ));
        }
    }

    public function editUser($id){
        header('Content-Type: application/json; charset=utf-8');
        //name,email,phone,roles,access

        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);

        if($bad = $this->validate_for_edit($date)){
            $this->render(array(
                'code' => 400,
                'error'=>'invalid parameters',
                'description'=> $bad
            ));
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
            $this->render(array(
                'code' => 200,
                'date'=>'user update successfully '
            ));
        } else {
            $this->render(array(
                'code' => 400,
                'error' => $user['date']
            ));
        }
    }

    // метод для вализации данных
    // возвращает bool значение
    private function validate($date){
        $errors = [];
        if($date['name'] == ''){
            $errors[] = 'not valid name';
        }

        if(!filter_var($date['email'], FILTER_VALIDATE_EMAIL)){
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
        //roles
        if(!in_array($date['roles'], array('user','administrator'), true )){
            $errors[] = 'invalid roles. is available (user, administrator)';
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

        if(!filter_var($date['email'], FILTER_VALIDATE_EMAIL)){
            $errors[] = 'not valid email';
        }

        if(!in_array($date['roles'], array('user','administrator'), true )){
            $errors[] = 'invalid roles. is available (user, administrator)';
        }

        if(!in_array($date['access'], array(1,0), true )){
            $errors[] = 'invalid access. is available ( 1 = open, 0 = close)';
        }
        return $errors;
    }
}