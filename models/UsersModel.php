<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 22.10.2018
 * Time: 17:40
 */

include_once './core/DB.php';

class UsersModel
{
    //класс для работы с бд
    private $db;

    public function __construct()
    {
        $dbh = new DB;
        $this->db = $dbh->db();
    }

    public function getUser($id)
    {
        $sth = $this->db->prepare('select `name`,email,roles,created_at,phone,access from users where id = :id limit 1');

        $params = [
            ':id' => $id
        ];

        $sth->execute($params);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function checkUser($userEmail){
        $sth = $this->db->prepare('select count(*) as cnt from users where email=:email limit 1');

        $params = [
            ':email' => $userEmail
        ];

        $sth->execute($params);
        $res = $sth->fetch(PDO::FETCH_ASSOC);

        return ($res['cnt'] == '0' ? FALSE : TRUE);
    }

    public function createUser($user){

            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $sth = $this->db->prepare('insert into users (`name`,`email`,`password`,`roles`,`phone`) values (:name,:email,:password, :roles, :phone)');

            //роль хардкодим, админами будут узеры сделаные через базу данных
            //или модифицируем что бы админ мог сделать админа
            //роль администратора roles=administrator

            $params = [
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':password' => $user['password'],
                ':phone' => $user['phone'],
                ':roles' => $user['roles']
            ];

            if($sth->execute($params)){
                return array('status' => TRUE, 'date' => $this->db->lastInsertId());
            } else {
                return array('status' => FALSE,'date' => $sth->errorInfo());
            }


    }
    //name,email,phone,roles,access
    public function editUser($user){

            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $sth = $this->db->prepare('UPDATE users SET `name` = :name, `email` = :email, `phone` = :phone, `roles` = :roles, `access` = :access WHERE  id = :id');

            $params = [
                ':id' => $user['id'],
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':phone' => $user['phone'],
                ':roles' => $user['roles'],
                ':access' => $user['access'],
            ];

            $sth->execute($params);
            $count = $sth->rowCount();
            if($count){
                return array('status' => TRUE,'date' => $count);
            } else {
                return array('status' => FALSE,'date' => $sth->errorInfo());
            }
    }
}