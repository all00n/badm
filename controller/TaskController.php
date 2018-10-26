<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 25.10.2018
 * Time: 16:57
 */

include './models/TaskModel.php';

class TaskController extends Controller
{

    private $model;
    private $session;

    public function __construct()
    {
        $this->model = new TaskModel();
        $this->session = $GLOBALS['session'];
    }
    //метод для вывода всех задач
    public function getTasks(){
        $tasks = $this->model->getAllTask();
        if($tasks){
            $this->render(array('code'=>200,'tasks'=>$tasks));
        } else {
            $this->render(array('code'=>404,'date'=>'no records'));
        }
    }

    //метод для вывода одного таска с коментами
    public function getTask($task_id){
        $task = $this->model->getTask($task_id);

        if($task){
            $this->render(array('code'=>200,'task'=>$task));
        } else {
            $this->render(array('code'=>404,'date'=>'no records'));
        }
    }

    //метод для создания таска
    public function addTask(){
        //заголовок для ответа
        header('Content-Type: application/json; charset=utf-8');
        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);

        //валидируем входные данные
        if($bad = $this->validate($date)){
            echo json_encode(array(
                'code' => 400,
                'error'=>'invalid parameters',
                'description'=> $bad
            ));
            return;
        }

        //если всё в порядке создаём пользователя
        $task = $this->model->addTask(array(
            'title'         => $date['title'],
            'description'   => $date['description'],
            'author_id'     => $this->session['id']
        ));

        $this->render(array(
            'code' => 200,
            'task_id' => $task
        ));
    }

    //метод для создания коментария к таску
    public function addComment($task_id){
        //заголовок для ответа
        header('Content-Type: application/json; charset=utf-8');
        //считываем полученые данные
        $date = json_decode(file_get_contents('php://input'), true);

        //валидируем входные данные
        if($bad = $this->validateComment($date)){
            echo json_encode(array(
                'code' => 400,
                'error'=>'invalid parameters',
                'description'=> $bad
            ));
            return;
        }

        //если всё в порядке создаём пользователя
        $comment = $this->model->addComment(array(
            'task_id'       => $task_id,
            'description'   => $date['description'],
            'status'        => $date['status'],
            'author_id'     => $this->session['id']
        ));

        $this->render(array(
            'code' => 200,
            'comment_id' => $comment
        ));
    }

    private function validate($date){
        $errors = [];
        if($date['title'] == ''){
            $errors[] = 'not valid name';
        }

        if($date['description'] == ''){
            $errors[] = 'not valid email';
        }

        return $errors;
    }

    private function validateComment($date){
        $errors = [];

        if($date['description'] == ''){
            $errors[] = 'not valid email';
        }

        if(!in_array($date['status'], array('pending', 'done'), true )){
            $errors[] = 'invalid status. is available (pending, done)';
        }
        return $errors;
    }
}