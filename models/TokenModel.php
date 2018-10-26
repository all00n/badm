<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 23.10.2018
 * Time: 13:48
 */

include_once './core/DB.php';

class TokenModel
{
    private $db;


    //создаём екземпляр класса из ядра для работы с бд
    public function __construct()
    {
        $dbh = new DB;
        $this->db = $dbh->db();
    }
    //метод возвращает id пользователя и токен
    public function getPermission($token) {
        //достаём информацию о юзере по переданому токену
        //токен у нас уникальный по этому не боимся каких то старых записей
        $sql = 'SELECT u.id, roles FROM `token` t 
                JOIN `users` u on u.id=t.user_id 
                WHERE token = :token  and expire >= NOW() LIMIT 1';

        $stmt = $this->db->prepare($sql);

        $stmt->execute(array(':token' => $token));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function extension($token,$expire){
        $sth = $this->db->prepare('UPDATE token SET `expire` = now() + INTERVAL :expire SECOND WHERE  token = :token');

        $params = [
            ':token' => $token,
            ':expire' => $expire
        ];

        $sth->execute($params);
    }
}