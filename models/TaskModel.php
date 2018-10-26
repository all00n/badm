<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 25.10.2018
 * Time: 17:09
 */

include_once './core/DB.php';

class TaskModel
{
    private $db;

    public function __construct()
    {
        $dbh = new DB;
        $this->db = $dbh->db();
    }

    //получаем все таски
    public function getAllTask(){
        $sth = $this->db->query('SELECT t.id,t.title,t.description,CASE WHEN t.status = 1 THEN \'pending\' ELSE \'done\' END as status,t.created_at,u.name as author FROM task t join users u on t.author_id=u.id ORDER BY t.created_at DESC');

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    //выводим один таск с коментами
    public function getTask($id_task){
        $sth = $this->db->prepare('SELECT t.id,t.title,t.description,CASE WHEN t.status = 1 THEN \'pending\' ELSE \'done\' END as status,t.created_at,u.name as author FROM task t join users u on t.author_id=u.id WHERE t.id = :id LIMIT 1');

        $params = [
            ':id' => $id_task
        ];

        $sth->execute($params);
        $task = $sth->fetch(PDO::FETCH_ASSOC);

        $sth = $this->db->prepare('SELECT t.id,t.task_id,t.description,CASE WHEN t.status = 1 THEN \'pending\' ELSE \'done\' END as status,t.created_at,u.name as author FROM task_coments t join users u on t.author_id=u.id WHERE task_id = :task_id');

        $params = [
            ':task_id' => $id_task
        ];

        $sth->execute($params);
        $task['comments'] = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $task;
    }

    public function addTask($date){
        $sql = 'INSERT INTO task (title,description,author_id) VALUES (:title, :description, :author_id)';

        $sth = $this->db->prepare($sql);

        $sth->execute(array(
                    ':title'        => $date['title'],
                    ':description'  => $date['description'],
                    ':author_id'    => $date['author_id']
                ));
        return $this->db->lastInsertId();
    }

    public function addComment($date){
        $this->db->beginTransaction();
        try {
            //вносим комент в базу
            $sql = 'INSERT INTO task_coments (task_id,description,status,author_id) VALUES (:task_id, :description, :status, :author_id)';

            $sth = $this->db->prepare($sql);

            $sth->execute(array(
                ':task_id' => $date['task_id'],
                ':description' => $date['description'],
                ':status' => ($date['status'] == 'pending' ? 1:0),
                ':author_id' => $date['author_id']
            ));

            $com_id = $this->db->lastInsertId();

            //изменяем статус в задаче
            $stm = $this->db->prepare('UPDATE task SET status = :status WHERE  id = :id');

            $params = [
                ':id' => $date['task_id'],
                ':status' => ($date['status'] == 'pending' ? 1:0)
            ];

            $stm->execute($params);

            $this->db->commit();
            return $com_id;
        }
        catch (Exception $e){
            $this->db->rollBack();
            return $e->getMessage();
        }
    }
}